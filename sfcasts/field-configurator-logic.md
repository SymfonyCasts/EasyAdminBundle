# Field Configurator Logic

Coming soon...

Like the "value", "formatted value", "form type", "template path", and
much more. One thing you might notice is that this is a `FieldDto` class and when
we're in our CRUD controllers, we're dealing with `Field` classes. Interesting...
This is a pattern that EasyAdmin follows a lot. When we're just configuring things,
we'll have an easy class like `Field`, and `Field` will give us a lot of really nice
methods to do different things to configure that field.

Behind the curtain, the entire purpose of this `Field` object or any of these `Field`
classes is to take all of the information we give it and create a `FieldDto`. I'll
call `->formatValue()` temporarily and hold "cmd" to jump into that. This actually
moved us into a `FieldTrait.php` that field uses.

Check this out! When we call `formatValue()`, what that really does is say
`$this->dto->setFormatValueCallable()`. That Dto is the `FieldDto`. So we call nice
methods on our `Field` object, but in the background, it's just using all of that
information to craft this `FieldDto`. This means that this `FieldDto` contains
basically the same information as our `Field` objects here, but the data is going to
look different. The methods you call on them will also be a bit different.

All right, let's do our truncating here. Create a private constant: `private const
MAX_LENGTH = 25`. Down here, we'll say `if (strlen($field->getFormattedValue()))`,
where "formatted value" is just the string that it's about to print out, and then
just `return`. Don't make any changes to the field. Just allow the formatted value to
be returned like normal. Below, we want to truncate it. So say `$truncatedValue =`
and I'm going to use a `u()` function here. Hit "tab" to auto complete that and, just
like class, it's added a "use" statement up here. This gives me a nice Unicode string
object from the `Form` components.

Inside, I'll pass `$field->getFormattedValue()` and call `->truncate()` with
`self:MAX_LENGTH, '...', false`. The last argument just makes this truncate a little
cleaner. Oh, and I forgot one of my colons right there. That's better. Now, we're
going to say `$field->setFormattedValue()` and pass it `$truncatedValue`. We're
overriding what the formatted value *would* be.

If we go over and refresh... absolutely nothing happens. All of the items in this
column *still* have the same length as before. What's happening? We know our
configure methods are getting called. So do we have a bug in our code?

Well, when we create a class and make it implement `FieldConfiguratorInterface`,
thanks to Symfony's auto configure feature, this gives a special tag to our service
called `ea.field_configurator`. It turns out, that's the key to getting your field
into the configurator system. You *have* to have a service with that tag.

At your terminal, run:

```terminal
symfony console debug:container
```

And we can actually list all the services with that tag by saying:

```terminal
--tag=ea.field_configurator
```

Beautiful! You can see there are a bunch of them inside of here, and a couple, like
"CommonPreConfigurator" and "CommonPostConfigurator" have a priority on them, which
controls the order that they're in. It turns out, if you look into this a little bit,
you can see our "TruncateLongTextConfigurator" right here has a priority of zero,
like most of these. So it seems, by chance, our "TruncateLongTextConfigurator" is
being run before a *different* configurator that is overriding the set formatted
value. I believe it's actually this "TextConfigurator" down here. Let's actually see
if that's the case. Search for "TextConfigurator.php" and
I'll look at "All Places" and open up `TextConfigurator.php`.

Yep! The `TextConfigurator` operates on `TextField` and `TextareaField`, and one of
the things it does is set the formatted value. So our class is being called first,
we're setting the formatted value, and then a second later, this text configurator is
overriding our formatted value. This is why it's useful to be able to find these
classes and look inside of them. We want our configurer to be called *after* this. To
do that, we need to give our configurator a negative priority so that it's called at
the end. Go to `/config/services.yaml`... and this will be a rare moment where we
actually configure a service manually. Say
`App\EasyAdmin\TruncateLongTextConfigurator:`. We don't need to worry about the
arguments or anything. If we have any construct arguments, those will still be auto
wired. But we *do* need to add `tAJAX:` with `name: ea.field_configurator` and a
`priority: -1`, which is plenty for our purposes.

Whew... Le's go try this out. Refresh and... it *still* doesn't work. Rude... I'll
run my `debug:container` again. Yep! You can see our "-1" right there. So what gives?
Let me look over on our configurator.

Oh, *that's* what happened! I actually need to put `< self::MAX_LENGTH` right here.
To fully test this out, I'll comment out my configurator service here. That's just to
show you that it doesn't work originally thanks to the priority. If I put this back,
our priority should be back to "-1", and... beautiful! *Now* it's working. I messed
up a little bit in the middle, but that was a *smooth* recovery. If you look at the
detail page for this, you can see that it also truncates it there. Could we truncate
on the index page, but *not* on the details page? Totally! It's just a matter of
figuring out which we're on in our configurator.

One of the things this passes is `AdminContext`. We're going to talk more about this
later, but this is the object that holds all the information about your admin
section. So we can say `$crud = $context->getCrud()`. This is going to return the
CRUD object that we've been configuring inside of our CRUD controllers and our
`DashboardController.php`. Now add `if ($crud->getCurrentPage() ===
Crud::PAGE_DETAIL)`, then just `return` and do nothing.

Go refresh. Now we get the full entry right there. It's not really important, but
there *are* some edge cases where `$context` or `getCrud` will actually return null,
so I'm just going to code defensively. If I hold "cmd" or "ctrl" to open `getCrud`,
you can see it returns a `?CrudDto`, meaning there might not be a CRUD. So by adding
this here, it ignores that check.

Next, let's create a custom field.

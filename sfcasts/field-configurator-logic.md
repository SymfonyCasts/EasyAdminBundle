# Field Configurator Logic

We just bootstrapped a _field configurator_: a super-hero-like class where
we get to modify *any* field in *any* CRUD section from the comfort of our home.
We really *do* live in the future.

At this point in the process, what EasyAdmin gives us is something called
a `FieldDto`, which, as you can see, contains *all* the info about this field, like
its value, formatted value, form type, template path and much more.

## FieldDto vs Field

One thing you might have noticed is that this is a `FieldDto`. But when we're in
our CRUD controllers, we're dealing with the `Field` class. Interesting. This is
a pattern that EasyAdmin follows a lot. When we're configuring things, we use an
easy class like `Field`... where `Field` gives us a lot of nice methods to
control everything about it.

But behind the curtain, the *entire* purpose of the `Field` class - or any of the
other field classes - is to take *all* of the info we give it and create a `FieldDto`.
I'll call `->formatValue()` temporarily and hold `Cmd` or `Ctrl` to jump into that.
This moved us into a `FieldTrait` that `Field` uses.

And check it out! When we call `formatValue()`, what that *really* does is say
`$this->dto->setFormatValueCallable()`. That Dto is the `FieldDto`. So we call nice
methods on `Field`, but in the background, it uses all of that info to
craft this `FieldDto`. This means that the `FieldDto` contains the same info
as the `Field` objects, but its data, structure and methods are all a bit different.

## Truncating the Formatted Value

Ok: back to our goal of truncating long textarea fields. Add a
`private const MAX_LENGTH = 25` to keep track of our limit:

[[[ code('8976960f3e') ]]]

Then, below, `if (strlen($field->getFormattedValue()))` is less than or equal to
`self::MAX_LENGTH`, then just return:

[[[ code('135b7c3112') ]]]

And yes, I *totally* forgot about the `<= self::MAX_LENGTH` part. I'll add that later.
You should add it now.

Anyways, assuming you wrote this correctly, it says that if the formatted value
is already less than 25 characters, don't bother changing it: just let EasyAdmin
render like normal.

Below, let's truncate: `$truncatedValue =`... and I'll use the `u()` function.
Hit `Tab` to autocomplete that. Just like with a class, it added a `use`
statement on top:

[[[ code('b7bd224a45') ]]]

The `u` function gives us a `UnicodeString` object from Symfony's String component.

Pass this `$field->getFormattedValue()` and call `->truncate()` with
`self::MAX_LENGTH`, `...` and `false`:

[[[ code('a30cd7680f') ]]]

The last argument just makes truncate a little cleaner. Oh, and I forgot a colon
right there. That's better. Finally, call `$field->setFormattedValue()` and pass
it `$truncatedValue` to override what the formatted value *would* be:

[[[ code('453ed10ea5') ]]]

Let's try it! Move over, refresh and... absolutely nothing happens! All of the
items in this column *still* have the same length as before. What's happening?
It's not the bug in my code... something *else* is going on. But what?

## Field Configurator Order

When we create a class and make it implement `FieldConfiguratorInterface`, Symfony's
`autoconfigure` feature adds a special tag to our service called
`ea.field_configurator`. *That's* the key to getting your field into
the configurator system.

At your terminal, run `symfony console debug:container`. And we can actually list
all the services with that tag by saying `--tag=ea.field_configurator`:

```terminal-silent
symfony console debug:container --tag=ea.field_configurator
```

Beautiful! This shows, as expected, a *bunch* of services: all the core field
configurators plus our configurator. A few of these, like
`CommonPreConfigurator` and `CommonPostConfigurator` have a *priority*, which
controls the order in which they're called.

If you look closely, our `TruncateLongTextConfigurator` has a priority of 0, like
most of these. But, apparently by chance, our `TruncateLongTextConfigurator` is being
called before a *different* configurator that is then *overriding* our formatted
value! I believe it's `TextConfigurator`. Let's go see if that's the case. Search
for `TextConfigurator.php` and make sure to look in "All Places". Here it is!

And... yep! The `TextConfigurator` operates on `TextField` and `TextareaField`. And
one of the things it does is set the formatted value! So our class is called
first, we set the formatted value... and then a second later, *this* configurator
overrides that. Rude!

## Setting a Configurator Priority

The fix is to get *our* configurator to be called *after* this. To do that, it
needs a *negative* priority.

Open up `config/services.yaml`. This is a *rare* moment when we need to configure
a service manually. Add `App\EasyAdmin\TruncateLongTextConfigurator:`. We don't need
to worry about any potential arguments: those will still be autowired. But we *do*
need to add `tags:` with `name: ea.field_configurator` and `priority: -1`:

[[[ code('69c91ae123') ]]]

Autoconfiguration *normally* add this tag for us... but with a priority
of zero. By setting the tag manually, we can control that.

Whew! Testing time! Refresh and... it *still* doesn't work? Ok, *now* this is
my fault. In the configurator, add the missing `< self::MAX_LENGTH`:

[[[ code('4ae223c524') ]]]

To *fully* test this... and prove the priority was needed, I'll comment out my
configurator service. And... yup! The strings *still* aren't truncated. But if I
put that back... and try it... yes! It shortened!

Over on the detail page, it *also* truncates here. Could we... truncate on the
index page but *not* on the details page? Totally! It's just a matter of figuring
out what the current page is from inside the configurator.

## The All Powerful AdminContext

One of the arguments passed to us is `AdminContext`:

[[[ code('d05894815b') ]]]

We're going to talk more about this later, but this object holds *all* the information
about your admin section. For example, we can say `$crud = $context->getCrud()`
to fetch a CRUD object that's the result of the `configureCrud()` method in our
CRUD controllers and dashboard. Use this to say:
`if ($crud->getCurrentPage() === Crud::PAGE_DETAIL)`, then `return` and do nothing:

[[[ code('af7f85dadf') ]]]

Go refresh. Yes! We get the *full* text on the detail page. Btw, it's not too
important, but there *are* some edge cases where `$context->getCrud()` could
return null... so I'll code defensively:

[[[ code('20c35a1b96') ]]]

If you hold `Cmd` or `Ctrl` to open `getCrud()`, yup! It returns a nullable
`CrudDto`... though in practice, I think this is *always* set as long as
you're *on* an admin page.

Next: changing the formatted value for a field is great, but limited. What if you
want to render something *totally* different? Including custom markup and logic?
To do that, we can override the field template.

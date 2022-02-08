# Field Configurator

Let's finish configuring the rest of our fields and *then* talk about a super important system that's running behind the scenes: Field configurators.

We're still on `QuestionCrudController.PHP`. One other field that I want to render here is our "slug" field. Say `yield Field::new('slug')` and then `->hideOnIndex()`. This will just be there for the form system. Now, if I go to Questions... it's not there. If I *edit* a question, it *is* there. As you may already know, slugs are typically auto-generated from the name. When you're creating a question, you may want to customize the slug somehow. Once the slug has been created, however, it should never change.

On the create page, I want to allow the field to appear here like it is now, but on the *edit* page, I want to disable this field. I *could* remove the field entirely pretty easily by adding something like `->onlyWhenCreating()`, and that would only show the field when creating. What I *actually* want to do is show the field but just disable it while I'm editing.

As we know, every field has a form type behind it, and every form type in Symfony has an option called "disabled". We're going to say:

```
->setFormTypeOption(
    'disabled',
)
```

We don't want to just set this to "true" everywhere, because that would disable it everywhere. We just want to disable it on the edit page. This is where the `$pageName` argument that we passed is really handy. This will be something like "index" or "edit" or "details". So we can tell it to set "disabled" to "true" if `$pageName !==`, and I'll use the little `Crud::` class here and say `PAGE_NEW` so we can use that constant. Let's try it!

Over here on my edit page... it's disabled. If I go back to Questions and create a new question... we have a slug! All right, enough with the question section. Let's go over and configure the fields for our answers. I'll close `QuestionCrudController.php`, open `AnswersCrudController.php` uncomment `configureFields` because we usually want to control this... and then I'm going to paste in some fields. Oh! I just need to retype the end of these classes and hit "tab" so I can get my auto complete for them. Perfect!

There's nothing too special here. These are pretty basic right now. I might want to add auto complete on my "question" and my "answeredBy", but I'll leave that up to you.

If we refresh... the Answers page looks awesome! And if I edit one, we're going to get our *favorite* error:

>Object of class [...]\Question could not be converted to string

This is coming from our `AssociationField`. The solution is to go into `Question.php` and add `public function __toString(): string` and we're going to `return $this->name`. And now... that page works! 

Back on the Answers page... sometimes this text here might be a little too long to be showing on this index page. What I would like to do is truncate this if it's past a certain length. *Technically*, fixing this right here is really easy. We can go over here to our answer field, and if we change this to a `TextField`, then one of the things we can call in here is `->setMaxLength()`. I can literally tell it to set the max length to 50, and that's going to truncate for me if the line exceeds 50 characters. *But*, I'm going to undo that because what I want to do is see if I can solve this globally for my entire system.

We're using field right here, which we know has to guess its field type. This one is printing as a text area, so its field type is really a text area field. If I wanted to, I could type text area field right here. What I want to do is set a max length for every text area field across our entire system. We can do that with a field configurator. We talked about these a little bit earlier.

If you scroll down, I already have `/vendor/easycorp/easyadmin-bundle/` opened up. One of the directories in there is called `Field/` and it has a sub directory called `Configurator/`. After your field is created, it's passed through this configurator system. Any configurator can then make changes to that field. There are two really common configurators. `CommonPreConfigurator.php` is called when your field is created, and it does a number of different things to your field, including building the option, building whether it's required, making it sortable, setting it's template path, and other things. There's also a `CommonPostConfigurator.php`, which is run *after* your field is created. For the most part, however, these configurators are specific to the actual fields. So if you're using a field and something magical is happening behind the scenes, there's a good chance that it's coming from a configurator. For example, the `AssociationConfigurator.php` is a bit of a complicated one, but it sets up all kinds of stuff behind the scenes to finish that field.

This is important because it's a great way to look and understand what's going on under the hood, like why some field is behaving in some way or how you can extend it. But it's *also* great because you can create your own custom field configurators. So let's do just that. Up in our source directory... here we go... create a new directory called "EasyAdmin", and inside of that, a new PHP class called... how about "TruncateLongTextConfigurator". The only rule of these classes is that they need to implement a `FieldConfiguratorInterface`. Go to "Code Generate" or "cmd + N" on a Mac, and select "Implement Methods" to implement the two that we want.

How does this work? For every single field that's added in the system, EasyAdmin's going to call our `supports()` method and basically ask:

>Does our configurator want to operate on this specific field?

These typically `return $field->getFieldFqcn()` and `===` a specific field type. In our case, we're going to target `TextareaField` types: `TextareaField::class`.

If the field that's being created is a `TextareaField`, then we *do* want to modify it. If we return "true" from supports, then `configure()` is called. Inside `configure()`, just for now, let's `dd()` this `$field` variable right here.

All right, head over... it doesn't matter where I go, so I'll just go to the index page, and... boom! It gets hit! This field Dto is *full* of information like the "value", "formatted value", "form type", "template path", and much more. One thing you might notice is that this is a `FieldDto` class and when we're in our CRUD controllers, we're dealing with `Field` classes. Interesting... This is a pattern that EasyAdmin follows a lot. When we're just configuring things, we'll have an easy class like `Field`, and `Field` will give us a lot of really nice methods to do different things to configure that field.

Behind the curtain, the entire purpose of this `Field` object or any of these `Field` classes is to take all of the information we give it and create a `FieldDto`. I'll call `->formatValue()` temporarily and hold "cmd" to jump into that. This actually moved us into a `FieldTrait.php` that field uses.

Check this out! When we call `formatValue()`, what that really does is say `$this->dto->setFormatValueCallable()`. That Dto is the `FieldDto`. So we call nice methods on our `Field` object, but in the background, it's just using all of that information to craft this `FieldDto`. This means that this `FieldDto` contains basically the same information as our `Field` objects here, but the data is going to look different. The methods you call on them will also be a bit different.

All right, let's do our truncating here. Create a private constant: `private const MAX_LENGTH = 25`. Down here, we'll say `if (strlen($field->getFormattedValue()))`, where "formatted value" is just the string that it's about to print out, and then just `return`. Don't make any changes to the field. Just allow the formatted value to be returned like normal. Below, we want to truncate it. So say `$truncatedValue =` and I'm going to use a `u()` function here. Hit "tab" to auto complete that and, just like class, it's added a "use" statement up here. This gives me a nice Unicode string object from the `Form` components.

Inside, I'll pass `$field->getFormattedValue()` and call `->truncate()` with `self:MAX_LENGTH, '...', false`. The last argument just makes this truncate a little cleaner. Oh, and I forgot one of my colons right there. That's better. Now, we're gonna say `$field->setFormattedValue()` and pass it `$truncatedValue`. We're overriding what the formatted value *would* be.

If we go over and refresh... absolutely nothing happens. All of the items in this column *still* have the same length as before. What's happening? We know our configure methods are getting called. So do we have a bug in our code?

Well, when we create a class and make it implement `FieldConfiguratorInterface`, thanks to Symfony's auto configure feature, this gives a special tag to our service called `ea.field_configurator`. It turns out, that's the key to getting your field into the configurator system. You *have* to have a service with that tag.

At your terminal, run:

```terminal
symfony console debug:container
```

And we can actually list all the services with that tag by saying:

```terminal
--tag=ea.field_configurator
```

Beautiful! You can see there are a bunch of them inside of here, and a couple, like "CommonPreConfigurator" and "CommonPostConfigurator" have a priority on them, which controls the order that they're in. It turns out, if you look into this a little bit, you can see our "TruncateLongTextConfigurator" right here has a priority of zero, like most of these. So it seems, by chance, our "TruncateLongTextConfigurator" is being run before a *different* configurator that is overriding the set formatted value. I believe it's actually this "TextConfigurator" down here. Let's actually see if that's the case. Search for "TextConfigurator.php" and
I'll look at "All Places" and open up `TextConfigurator.php`.

Yep! The `TextConfigurator` operates on `TextField` and `TextareaField`, and one of the things it does is set the formatted value. So our class is being called first, we're setting the formatted value, and then a second later, this text configurator is overriding our formatted value. This is why it's useful to be able to find these classes and look inside of them. We want our configurer to be called *after* this. To do that, we need to give our configurator a negative priority so that it's called at the end. Go to `/config/services.yaml`... and this will be a rare moment where we actually configure a service manually. Say `App\EasyAdmin\TruncateLongTextConfigurator:`. We don't need to worry about the arguments or anything. If we have any construct arguments, those will still be auto wired. But we *do* need to add `tags:` with `name: ea.field_configurator` and a `priority: -1`, which is plenty for our purposes.

Whew... Le's go try this out. Refresh and... it *still* doesn't work. Rude... I'll run my `debug:container` again. Yep! You can see our "-1" right there. So what gives? Let me look over on our configurator.

Oh, *that's* what happened! I actually need to put `< self::MAX_LENGTH` right here. To fully test this out, I'll comment out my configurator service here. That's just to show you that it doesn't work originally thanks to the priority. If I put this back, our priority should be back to "-1", and... beautiful! *Now* it's working. I messed up a little bit in the middle, but that was a *smooth* recovery. If you look at the detail page for this, you can see that it also truncates it there. Could we truncate on the index page, but *not* on the details page? Totally! It's just a matter of figuring out which we're on in our configurator.

One of the things this passes is `AdminContext`. We're going to talk more about this later, but this is the object that holds all the information about your admin section. So we can say `$crud = $context->getCrud()`. This is going to return the CRUD object that we've been configuring inside of our CRUD controllers and our `DashboardController.php`. Now add `if ($crud->getCurrentPage() === Crud::PAGE_DETAIL)`, then just `return` and do nothing.

Go refresh. Now we get the full entry right there. It's not really important, but there *are* some edge cases where `$context` or `getCrud` will actually return null, so I'm just going to code defensively. If I hold "cmd" or "ctrl" to open `getCrud`, you can see it returns a `?CrudDto`, meaning there might not be a CRUD. So by adding this here, it ignores that check.

Next, let's create a custom field.

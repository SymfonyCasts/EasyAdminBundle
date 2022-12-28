# Deep Field Configuration

One other property that we have inside of `User` is `$roles`, which actually stores
an *array* of the roles this user should have:

[[[ code('24694ac22e') ]]]

That's *probably* a good thing to include on our admin page. And fortunately,
EasyAdmin has an `ArrayField`!

## ArrayField

Check it out! Say `yield ArrayField::new('roles')`:

[[[ code('bbba042727') ]]]

And then head back to your browser. Over on the index page... nice! It renders as
a comma-separated list. And on the "Edit" page... oh, that's really cool! It added
a nice widget for adding and removing roles!

## Adding Help Text to Fields

The only tricky part might be remembering *which* roles are available. Right now,
you have to type each in manually. We can at least help our admins by going
back to our array field and implementing a method called `->setHelp()`. Add
a message that includes the available roles:

[[[ code('50fe784f96') ]]]

Now when we refresh... much better!

## ->setFormType() and ->setFormTypeOptions()

But, hmm. Now that I see this, it might look *even* better if we had check boxes.
So let's see if we can *change* the `ArrayField` to display check boxes. Hold
`Cmd` and open this core class.

This is *really* interesting, because you can actually *see* how the field is
configured inside of its `new()` method. It sets the template name (we'll talk
about templates later), but it *also* sets the form type. Behind the scenes,
the `ArrayField` uses a `CollectionType`. If you're familiar with the Symfony
Form Component, you know that, to render check boxes, you need the `ChoiceType`.
I wonder if we can *use* `ArrayField`... but override its form type to be
`ChoiceType`.

Let's... give it a try!

First, above this, add `$roles = []` and list our roles. Then, down here, after
`->setHelp()`, one of the methods we can call is `->setFormType()`... there's also
`->setFormTypeOptions()`. Select `->setFormType()` and set it to `ChoiceType::class`:

[[[ code('9b1814a144') ]]]

*Then* `->setFormTypeOptions()`... because one of the options that you *must* pass
to this form type is `choices`. Set this to `array_combine()` and pass `$roles` twice:

[[[ code('b5ef65869c') ]]]

I love rolls!

I know, that looks weird. This will create an array where these are both the keys
*and* the values. The result is that these will be *both* the values that are saved
to the database if that field is checked *and* what is displayed to the user. Lastly,
set `multiple` to `true` - because we can select multiple roles - and
`expanded` to `true`... which is what makes the `ChoiceType` render as check boxes:

[[[ code('5341461c67') ]]]

Alrighty! Let's see what happens. Refresh and... it... explodes! Exciting!

> An error occurred resolving the options of `ChoiceType`: The options
> `allow_add`, `allow_delete`, `delete_empty`, `entry_options` and `entry_type`
> do not exist.

Hmm... I recognize these options as options that belong to the `CollectionType`,
which is the type that the `ArrayField` was *originally* using. This tells me that
something, *somewhere* is trying to add these options to our form type... which
we don't want because... we're not using `CollectionType` anymore!

So... who *is* setting those options? This is tricky. You might expect to see them
set inside of `ArrayField`. But... it's not here! What mysterious being is messing
with our field?

## Hello Field Configurators

The answer is something called a _Configurator_.

Scroll back down to `vendor/`. I've already opened `easycorp/easyadmin-bundle/src/`.
Earlier, we were looking at the `Field/` directory: these are all the built-in fields.

After a field is created, EasyAdmin runs each through a `Configurator` system
that can make *additional* changes to it. This `Configurator/` directory
holds *those*. There are a couple of them - like `CommonPreConfigurator` - that are
applied to *every* field. It returns `true` from `supports()`... and does various
normalizations on the field. `CommonPostConfigurator` is another that applies to
every field.

But *then*, there are also a bunch of configurators that are specific
to just *one*... or maybe a few... field types, including `ArrayConfigurator`.
This configurator does its work when the `$field` is an `ArrayField`. The
`$field->getFieldFqcn()` is basically helping to ask:

> Hey, is the current field that's being configured an `ArrayField`? If it is,
> then call my `configure()` method so I can do some stuff!

And... yup! *Here* is where those options are being added. The Configurator system
is something we're going to look at more later. Heck we're even going to create our
own! For now, just be aware it exists.

## Refactoring to ChoiceField

So, hmm. In our situation, we *don't* want the `ArrayConfigurator` to do its work.
But, unfortunately, we don't really have a choice! The Configurator is *always* going
to apply its logic if we're dealing with an `ArrayField`.

And actually, that's fine! Back in `UserCrudController.php`, I didn't realize it
at first, but there's also a `ChoiceField`!

[[[ code('3cb0eed2ae') ]]]

Hold `Cmd` or `Ctrl` to open it. Yup, we can see that it already uses `ChoiceType`.
*So*, we don't need to take `ArrayField` and try to turn it *into* a choice...
there's already a built-in `ChoiceField` *made* for this!

And now we don't need to set the form type... and we don't need the help or the
form type options. I probably *could* set the choices that way, but the
`ChoiceField` has a special method called `->setChoices()`. Pass that same
thing: `array_combine($roles, $roles)`. For the other options, we can say
`->allowMultipleChoices()` and `->renderExpanded()`:

[[[ code('e9b57b211f') ]]]

How nice is that?

Let's try this thing. Refresh and... *that* is what I was hoping for! Back on
the index... `ChoiceType` *still* renders as a nice comma-separated list.

Oh, and by the way: if you want to see the logic that makes `ChoiceType` render
as a comma-separated list, there a `ChoiceConfigurator.php`. If you open that...
and scroll to the bottom - beyond a lot of normalization code - here it is:
`$field->setFormattedValue()` where it implodes the `$selectedChoices` with a comma.

## Rendering ChoiceList as Badges

Oh, and speaking of this type - let me close some core classes - one other
method we can call is `->renderAsBadges()`:

[[[ code('e7e4282a44') ]]]

That affects the "formatted value" that we just saw... and turns it into these
little guys. Cute!

Next, let's handle our user's `$avatar` field, which needs to be an upload field!

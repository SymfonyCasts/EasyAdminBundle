# Creating a Custom Field

On the Answers CRUD, we just created this nice custom "Votes" template, which we
then *use* by calling `->setTemplatePath()` on the `votes` field. But we *also* have
a votes field over in the Questions section... which still renders the *boring* way.
I want to use our new template in *both* places.

Technically, doing this is super easy! We could copy `->setTemplatePath()`,
go up, open `QuestionCrudController`, find the `votes` field... then paste to
use that template path.

But *instead*, let's create a *custom* field.

We know that a field class - like `TextareaField` or `AssociationField` - defines
how a field looks on the index and detail pages... as well as how it's rendered
on a form. A custom field is a *great* way to encompass a *bunch* of custom field
configuration in one place so you can reuse it. And *creating* a custom field is
pretty easy.

## Creating the Custom Field

Down in the `src/EasyAdmin/` directory, create a new PHP class called, how about,
`VotesField`.

The only rule for a field is that it needs to implement `FieldInterface`. This requires
us to have two methods: `new` and `getAsDto()`. But what you'll typically do is
`use FieldTrait` to make life easier.

[[[ code('7822da0fe2') ]]]

Click to open that. Ok, this `FieldTrait` helps manage the `FieldDto` object, with
a bunch of useful methods like `setLabel()`, `setValue()` and `setFormattedValue()`
that all fields share.

So *now*, if you go to Code Generate - or "cmd + N" on a Mac - the only thing we
need to implement is `new()`. *This* is where we customize all the options
for the field.

[[[ code('b8ab74ed77') ]]]

Our votes field is *currently* an `IntegerField`. Hold "cmd" or "ctrl"
to open that and look at *its* `new()` method... because we want *our* method to
look *very* much like this... with a few differences. So copy all of this, close,
head to `VotesField`... and paste. Hit "Ok" to add that use statement on top.
I'll also remove `OPTION_NUMBER_FORMAT`. We won't need that... and it relates to
a field configurator that I'll show you in a minute.

[[[ code('72ba949b0b') ]]]

Ok, good start! You may have noticed that `->setDefaultColumns()` is crossed
out. That's because it's marked as "internal". That *usually* means it's a function
that *we* shouldn't use directly. But in this case, the documentation says that
it *is* ok to use from *inside* of a field class... which is where we are!

At this point, we can customize *anything*! Like `->addWebpackEncoreEntries()` to
add an extra Webpack Encore entry that will be included when this field is used.
What *we* want to do, instead of calling `->setTemplateName()` so that it uses
the standard integer field template, is to say `->setTemplatePath()` and pass the
same thing we have in `AnswerCrudController`, which is `admin/field/votes.html.twig`.

As a reminder to myself, I'll add some comments about which part controls the
index and detail pages... and which part controls the form.

[[[ code('e3c40ac9ed') ]]]

## Using the Custom field

Ok, that's it! Let's go use this!

In `AnswerCrudController`, change this to `VotesField`... and we don't need
`->setTemplatePath()` anymore. 

[[[ code('3d4611b245') ]]]

Then, in `QuestionCrudController`, do the same thing.
Add `VotesField` and... done! If we wanted to, we could even put this
`->setTextAlign('right')` *inside* the custom field... *or* remove it.

[[[ code('ea92e96f36') ]]]

Testing time! Over in Questions, refresh and... got it! And on the Answers page...
it looks great there too!

## Watch out for Missing Configurators

But one tiny word of warning. Now that we've changed from `IntegerField` to
`VotesField`, *if* there's a field configurator for the `IntegerField`, it will
*no* longer be used.

And... there *is* such a configurator. Back down in
`vendor/easycorp/easyadmin-bundle/src/Field/Configurator`, you'll find
`IntegerConfigurator`. This operates *only* when the field you're using is an
`IntegerField`. And so, this configurator *was* being used until a second ago... but
not anymore.

If you look inside, it does some work with a custom number format, which allows
you to control the *format* that the number is printed. We don't really need this,
but don't forget about the "field configurator" system... and how a *custom*
field won't be processed in the same way.

Next, let's learn how to configure a bit more of the CRUD itself, like how a CRUD
section is sorted by default, pagination settings, and more.

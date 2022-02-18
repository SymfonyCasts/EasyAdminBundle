# The Field Configurator System

Let's finish configuring a few more fields and *then* talk more about a crazy-cool
important system that's working behind the scenes: _field configurators_.

## Disabling a Form Field only on Edit

One other field that I want to render in the question section is "slug": `yield
Field::new('slug')`... and then `->hideOnIndex()`:

[[[ code('05717465df') ]]]

This will just be for the forms.

Now, when we go to Questions... it's not there. If we *edit* a question, it *is*
there. Slugs are typically auto-generated... but occasionally it *is* nice to
control them. However, once a question has been created and the slug set, it
should *never* change.

And so on the *edit* page, I want to *disable* this field. We *could* remove it
entirely by adding `->onlyWhenCreating()`... but pff. That's too easy! Let's *show*
it, but disable it.

How? We already know that each field has a form type behind it. And each form type
in Symfony has an option called `disabled`. To control this, we can say
`->setFormTypeOption()` and pass `disabled`:

[[[ code('5748aa1ddb') ]]]

But we can't just set this to "true" everywhere... since that would disable it
on the new page. *This* is where the `$pageName` argument comes in handy! It'll
be a string like `index` or `edit` or `details`. So we can set `disabled`
to `true` if `$pageName !==`... and I'll use the `Crud` class to reference its
`PAGE_NEW` constant:

[[[ code('c7a0905495') ]]]

Let's do this! Over here on the edit page... it's disabled. And if we go back to
Questions... and create a new question... we have a *not* disabled slug field!

Ok, enough with the question section! Close `QuestionCrudController` and open
`AnswerCrudController`. Uncomment `configureFields()`... and then I'll paste in
some fields. Oh! I just need to retype the end of these classes and hit `Tab` to
auto-complete them... to get the missing `use` statements:

[[[ code('bd0af0137e') ]]]

Perfect There's nothing special here. You might want to add autocomplete to
the `question` and `answeredBy` fields, but I'll leave that up to you.

If we refresh... the Answers page looks awesome! And if we edit one, we get our
*favorite* error:

> Object of class `Question` could not be converted to string

This comes from the `AssociationField`. The solution is to go into `Question.php`
and add `public function __toString(): string`... and `return $this->name`:

[[[ code('0d29bd69c6') ]]]

And now... that page works too!

## Globally Changing a Field

Back on the main Answers page... sometimes this text might be too long to fit
nicely in the table. Let's truncate it if it's longer than a certain length.
Doing this is... really easy. Head over to the `answer` field, use `TextField`...
and then leverage a custom method `->setMaxLength()`:

```php
public function configureFields(string $pageName): iterable
{
    // ...
    yield TextField::new('answer')
        // ...
        ->setMaxLength(50);
}
```

If we set this to 50, that will truncate any text that's longer than 50 characters!

*But*, I'm going to undo that. Why? Because I want us to do something more interesting!

Right now, I'm using `Field` which tells EasyAdmin to guess the best field type.
This is printing as a textarea... so its field type is *really* `TextareaField`...
and we can use that if we want to.

## More about Field Configurators

Here's the new goal: I want to set a max length for *every* `TextareaField` across
our *entire* app. How can we change the behavior of *many* fields at the same
time? With a field configurator.

We talked about these a bit earlier. Scroll down: I already have
`/vendor/easycorp/easyadmin-bundle/` opened up. One of the directories is
called `Field/`... and it has a subdirectory called `Configurator/`. After your
field is created, it's passed through this configurator system. Any configurator
can then make changes to any field. There are two "common" configurators.
`CommonPreConfigurator` is called when your field is created, and it does a number
of different things to your field, including building the label, setting whether
it's required, making it sortable, setting its template path, etc.

There's also a `CommonPostConfigurator`, which runs *after* your field is created.

But mostly, these configurators are specific to one or just a few field *types*.
And if you're ever using a field and something "magical" is happening behind the
scenes, there's a good chance that it's coming from one of these. For example, the
`AssociationConfigurator` is a bit complex... but it sets up all *kinds* of stuff
to get that field working.

Knowing about these is important because it's a great way to understand what's going
on under the hood, like why some field is behaving in some way or how you can extend
it. But it's *also* great because we can create our *own* custom field configurator!

Let's do just that. Up in `src/`... here we go... create a new directory called
`EasyAdmin/` and, inside, a new PHP class called... how about
`TruncateLongTextConfigurator`. The only rule for these classes is that they need
to implement a `FieldConfiguratorInterface`:

[[[ code('754625bbdf') ]]]

Go to "Code"->"Generate" or `Cmd`+`N` on a Mac, and select "Implement Methods"
to implement the two that we need:

[[[ code('dd79dc2a10') ]]]

Here's how this works. For *every* field that we return in `configureFields()`
for *any* CRUD section, EasyAdmin will call the `supports()` method on our new class
and basically ask:

> Does this configurator want to operate on this specific field?

These typically `return $field->getFieldFqcn() ===` a specific field type. In
our case, we're going to target textarea fields: `TextareaField::class`:

[[[ code('2553f266c3') ]]]

If the field that's being created is a `TextareaField`, then we *do* want to modify
it. Next, *if* we return `true` from supports, EasyAdmin calls `configure()`.
Inside, just for now, `dd()` the `$field` variable:

[[[ code('91a7ece907') ]]]

Let's see if it triggers! Find your browser. It doesn't matter where I go, so I'll
just go to the index page. And... boom! It hits! This `FieldDto` is *full* of
info *and* full of ways to *change* it.

Let's dive into it next, including how this `FieldDto` relates to the `Field`
objects that we return from `configureFields()`.

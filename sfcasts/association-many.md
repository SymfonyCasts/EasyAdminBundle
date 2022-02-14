## AssociationField for a "Many" Collection

There's one other `AssociationField` that I want to include inside this CRUD section
and it's an interesting one: `$answers`. Unlike `$topic` and `$answeredBy`, this is a
`Collection`: each `Question` has *many* answers.

Back in `QuestionCrudController`, `yield AssociationField::new('answers')`.

And.... let's just see what happens! Click back to the index page and... awesome!
It recognizes that it's a Collection and prints the number of answers that each
`Question` has... which is pretty sweet. And if we go to the form, I'm really
starting to like this error! The form is, once again, trying to get a string
representation of the entity.

## Configuring the choice_label Field Option

We know how to fix this: head over to `Answer.php` and add the `__toString()`
method. *But*, there's actually one *other* way to handle this. If you're familiar
with the Symfony Form component, then this problem of converting your entity into a
string is something that you see all the time with the `EntityType`. The two ways to
solve it are either to add the `__toString()` method to your entity, *or* pass your
field a `choice_label` option. We can do that here thanks to the
`->setFormTypeOption()` method.

Before we fill that in, open up the `AssociationField` class... and scroll down to
`new`. Behind the scenes, this uses the `EntityType` for the form. So any options
`EntityType` has, *we* have. For example, we can set `choice_label`, which accepts
a callback or just the property on the entity that it should use. Let's try
`id`.

And now... beautiful! The ID isn't super clear, but we *can* see that it's working.

## by_reference => false

Let's... try removing a question! Remove "95", hit "Save and continue editing"
and... uh. Absolutely nothing happened? Answer id 95 is still there!

If you're familiar with collections and the Symfony Form component, you might
know the fix. Head over and configure one other form type option called `by_reference`
set to `false`.

I won't go into too much detail, but basically, by setting `by_reference` to `false`,
if an answer is *removed* from this question, it will force the system to call
the `removeAnswer()` method that I have in `Question`. That method properly removes
the `Answer` from `Question`. But more importantly, it sets `$answer->setQuestion()`
to `null`, which is the *owning* side of this relationship... for you Doctrine geeks
out there.

## orphanRemoval

Ok, try removing "95" again and saving. Hey! We upgraded to an error!

> An exception occurred, [...] Not null violation: [...] null value in
> column "question_id" of relation "answer"

So what happened? Open `Question.php` back up. When we remove an answer from `Question`,
our method sets the `question` property on the `Answer` object to "null". This makes
that `Answer` an "orphan": its an `Answer` that is no longer related to *any* `Question`.

However, inside `Answer`, we have some code that prevents this from ever
happening: `nullable: false`. If we ever try to save an Answer without a Question,
our database will stop us.

So we need to decide what should happen when an answer is "orphaned". In some
apps, maybe orphaned answers are ok. In that case, change to `nullable: true`
and let it save. But in *our* case, if an answer is removed from its question,
it should be *deleted*.

In Doctrine, there's a way to force this and say:

> If an `Answer` ever becomes orphaned, please delete it.

It's called "orphan removal". Inside of `Question`, scroll up to find the `$answers`
property... here it is. On the end, add `orphanRemoval` set to `true`.

Now refresh and... yes! It worked! The "95" is gone! And if you looked in the database,
no answer with "ID 95" would exist. Problem solved!

## Customizing the AssociationField

The last problem with this answers area is the *same* problem we have with the other
ones. If we have many answers in the database, they're *all* going to be loaded onto
the page to render the `select`. That's not going to work, so let's add
`->autocomplete()`.

When we refresh, uh oh!

> Error resolving CrudAutocompleteType: The option "choice_label" does not exist.

Ahhh. When we call `->autocomplete()`, this *changes* the form type behind
`AssociationField`. And *that* form type does *not* have a `choice_label` option!
Instead, it *always* relies on the `__toString()` method of the entity to display
the options, no matter what.

No big deal. Remove that option. You can probably guess what will happen if we
refresh. Yup! *Now* it's saying:


> Hey Ryan! Go add that `__toString()` method!

Ok fine! In `Answer`, anywhere in here, add `public function __toString(): string`...
and `return $this->getId()`.

Now... we're back! And if we type... well... the search isn't *great* because
it's just numbers, but you get the idea. Hit save and... nice!

Next, let's dig into the powerful Field Configurators system where you can modify
something about *every* field in the system from one place. It's also key to
understanding how the core of EasyAdmin works.

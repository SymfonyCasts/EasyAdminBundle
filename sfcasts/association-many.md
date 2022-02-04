## Adding AssociationField for a "Many" Collection

All right, there's one other `AssociationField` that I want to include on this CRUD,
and it's an interesting one: `$answers`. Unlike `$topic` and `$answeredBy`, this is a
`Collection`, because each question has *many* answers.

Back in `QuestionCrudController.php`, add `yield AssociationField::new('answers')`,
and let's just see what we have here. I'll click back to the index page and...
awesome! By default, it recognizes that it's a Collection, so it prints the number of
answers that each has, which is pretty sweet. And if we go to a form, we get a very
familiar error. The form is once again trying to get a string representation of our
entity.

We know how to fix this. We need to go to `Answer.php` and add the `__toString()`
method. *But*, there's actually one *other* way to handle this. If you're familiar
with the Symfony Form components, then this problem of converting your entity into a
string is something that you see all the time with the `EntityType`. The two ways to
solve it are either to add the `__toString()` method to your entity, *or* you can
pass your form field a `choice_label` option. And we can do that here, because
remember, there *is* a `->setFormTypeOption()` method that we can pass here.

Before I fill that in, let me open up the `AssociationField` here, and I'm going to
scroll down to `new`. Behind the scenes, this is actually using the `EntityType`. So
any options the `EntityType` has,  *we* have. We can set `choice_label` here, and you
can either set this to a callback or just the property on each entity that you want
to use. Let's just use `id`.

And now... beautiful! The ID isn't super clear, but you can see that it's working.
Let's try removing them. I'll remove "95" and hit "Save and continue editing" and...
it went absolutely nowhere. If you're familiar with collections and the Symfony Form
component, you might remember the fix. I'll go over and set one other form type
option - `setFormTypeOption()` - called `by_reference` which is set to `false`. I
won't go into too much detail, but basically, by setting `by_reference` to `false`,
if an answer is *removed* from this question, that will force the system to call a
`removeAnswer()` method that I have on my question. That properly removes it from my
entity and even sets the `$answer->setQuestion()` to `null`. If you want to learn
more about that, you can search for "by_reference".

If I go back and remove "95" again and hit "Save", we get a *different* error.

> An exception occurred, [...] Not null violation: [...] null value in
> column "question_id" of relation "answer"

So what happened here? Let me load my `Question.php` entity back up.

When we remove an answer from Question, what we do is we actually set the question on
the answer to "null". This makes the answer we removed what's referred to as an
"orphan". After this change, that answer is no longer related to a question. However,
inside the `Answer.php` entity, we have some code that says that this should never
happen: `nullable: false`. We should never have an answer that isn't connected to a
question. So now we need to decide what we want to do here. If I remove an answer
from a question, what should happen? What *should* happen is that answer should just
be deleted. In Doctrine, there's a way to force this and say:

> "Hey, if an answer becomes orphaned, I want you to delete it."

It's called "orphan removal". So inside of `Question.php`, scroll up to find the
`$answers` property. Here it is. On the end of this, add `orphanRemoval:` set to
`true`.

If we refresh... yes! It worked! The "95" is gone! And if you look in the database,
an answer with "ID 95" doesn't exist. Awesome!

The last problem with this answers area is the *same* problem we have with the other
ones. If we have many answers in the database, they're *all* going to be loaded onto
the page to render the select element that's behind this. That's obviously not going
to work. Well just have to solve it in the same way, by adding `->autocomplete()`.

When we refresh, we're going to get this error that says that the "AutoComplete Type"
does not have a "choice_label" option. Behind the scenes, when we call
`->autocomplete()`, this actually changes the form type behind the
`AssociationField`. And *that* form type doesn't have a `choice_label` option. It
always relies on the `__toString()` method of your entity, no matter what. So I'll
remove that, and you can probably guess what's going to happen next. *Now* it's
saying:

> "Okay, I need to have a `__toString()` method on answer."

So, let's finally add one. In `Answer.php`, I'll go to the bottom and say `public
function __toString(): string`, and then `return $this->getId()`. And now...
beautiful! It looks the same, and if I search for something... the search isn't
*great* because it's just my numbers, but you get the idea. I'll save and... nice!

Next, we're going to talk a little bit more about fields. *But*, what we're really
going to focus on is a power powerful system called Field Configurators, where you
can modify something about *every* field in the system from one place. It's also a
key to understanding how the core of EasyAdmin works.

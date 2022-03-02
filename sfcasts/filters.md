# The Filter System

Let's go log out... and then log *back* in as our "super admin" user:
"superadmin@example.com"... with "adminpass". Now head back to the admin area, find
the Users list and... perfect! As promised, we can see *every* user in the system.

Our user list is pretty short right now, but it's going to get longer and longer
as people realize... just how amazing our site is. It would be *great* if we could
filter the records in this index section by some criteria, for example, to only
show users that are enabled or *not* enabled. Fortunately, EasyAdmin has a system
for this called, well, filters!

## Hello configureFilters()

Over in `UserCrudController`, I'll go to the bottom and override yet another method
called `configureFilters()`.

This looks and feels a lot like `configureFields()`: we can call `->add()` and then
put the name of a field like `enabled`.

And... that's all we need! If we refresh the page, watch this section around the
top. We have a new "Filters" button! That opens up a modal where we can filter
by whichever fields are available. Let's say "Enabled", "No" and... *all* of
these are gone because *all* of our users *are* enabled.

We can go and change that... or clear the filter entirely.

## Filter Types

Ok: notice that `enabled` in our entity is a boolean field... and EasyAdmin detected
that. It knew to make this as a "Yes" or "No" checkbox. Just like with the *field*
system, there are also many different types of *filters*. And if you just add a
filter by saying `->add()` and then the property name, EasyAdmin tries to guess
the correct filter type to use.

But, you *can* be explicit. What we have now is, in practice,
identical to saying `->add(BooleanFilter::new('enabled'))`.

When we refresh now... and check the filters... that makes no difference because
that was already the filter type it was guessing.

Each filter class controls how that filter looks in the form up here, and *also*
how it modifies the *query* for the page. Hold cmd or ctrl and open the
`BooleanFilter` class. It has a `new()` method just like fields, and this sets
some basic information: the most important being the form type and any form type
options.

The `apply()` method is the method that will be called when the filter is *applied*:
it's where the filter *modifies* the query.

## Filter Form Type Options

Back in `new()`, this uses a form field called `BooleanFilterType`. Hold cmd or
ctrl to open *that*. Like all form types, this exposes a bunch of options that
allow us to control its behavior. Apparently there's an `expanded` option, which
is the reason that we're seeing this field as *expanded* radio buttons.

Just to see if we can, let's try changing that. Close that file... and after the
filter, add `->setFormTypeOption('expanded', false)`.

Try it now: refresh... head to the filters and... awesome! The non-expanded version
means it's rendered as a dropdown.

## The Many Filter Type Classes

Let's add some filters to the Questions section. Open `QuestionCrudController`
and, near the bottom, override `configureFilters()`. Start with an entity
relation. Each question has a `ManyToOne` relationship to `Topic`, so let's
`->add('topic')`.

Go refresh. We get the new filter section... and "Topic" is... this cool dropdown
list where we can select whatever topic we want!

To know how you can control this - or any - filter, you need to know what *type* it
is. Just like with fields, if you click on the filter class, you can see there's a
`src/Filter/` directory deep in the bundle. So
`vendor/easycorp/easyadmin-bundle/src/Filter/`... and here is the full list of all
possible filters.

I bet `EntityFilter` is the filter that's being used for the relationship.
By opening this up, we can learn about any methods it might have that will let
us configure it *or* how the query logic is done behind the scenes.

Let's add a few more filters, like `createdAt`... `votes`... and `name`.

And... no surprise, those all show up! The coolest thing is what they look like.
The `createdAt` field has a really easy way to choose dates, or even filter *between*
two dates. For Votes, you can choose "is equal", "is greater than", "is less than",
etc. And Name has different types of fuzzy searches that you can apply. *Super*
powerful.

We can also create our *own* custom filter class. That's as easy as creating
a custom class, making it implement `FilterInterface`, and using this `FilterTrait`.
*Then* all you need to do is implement the `new()` method where you set the
form type and then the `apply()` method where you modify the query.

Ok, right now, we have one "crud controller" per entity. But it's *totally* legal
to have *multiple* CRUD controllers for the *same* entity: you may have a situation
where each section shows a different filtered list. But even if you don't have this
use-case, adding a second CRUD controller for an entity will help us dive deeper
into how EasyAdmin works. That's next.

# Hello CRUD Controller

The true reason to use EasyAdmin is for its CRUD controllers. Each CRUD controller
will give us a rich set of pages to create, read, update, and delete a single entity.
This is where EasyAdmin *shines*, and the next few minutes are going to be *critically*
important to understand how EasyAdmin works. So, buckle up!

## Generating the CRUD Controller

We have four entities. Let's generate a CRUD controller for `Question` first. Find
your terminal and run:

```
symfony console make:admin:crud
```

As you can see, it recognizes our four entities. I'll hit 1 for `App\Entity\Question`,
let this generate into the default directory... and with default namespace.

Sweet! This did exactly *one* thing: it created a new `QuestionCrudController.php`
file. Let's... go open it up!

[[[ code('2afaa27cba') ]]]

## Linking to the CRUD Controller

Cool. But before we look too deeply into this, head over to the admin page and refresh
to see... absolutely no difference! We *do* have a new `QuestionCrudController`,
but these CRUD controllers are *totally* useless until we link to them from a
dashboard. So, back over in `DashboardController`, down at the bottom... `yield
MenuItem`... but instead of `linkToDashboard()`, there are a number of other things
that we can link to. We want `linkToCrud()`. Pass this the label - so "Questions" -
and some FontAwesome icon classes: `fa fa-question-circle`. Then, most importantly,
pass the entity's class name: `Question::class`:

[[[ code('04cf664079') ]]]

Behind the scenes, when we click this new link, EasyAdmin will recognize that
there is only *one* CRUD controller for the entity - `QuestionCrudController` -
and will know to use it. And yes, in theory, we *can* have multiple CRUD controllers
for a single entity... and that's something we'll talk about later.

Okay, go refresh to reveal our new link, click and... whoa! This is *amazingly* cool!
We have a slider for the `isApproved` field, which saves automatically. We also have
a search bar on top... and sortable columns to help us find whatever we're looking
for.

We can delete, edit... and the form even has a nice calendar widget. This is
*loaded* with rich features out-of-the-box.

## Generating All the CRUD Controllers

So let's repeat this for our other three controllers. Head back to your terminal
and, once again, run:

```terminal
symfony console make:admin:crud
```

This time generate a CRUD for `Answer`... with the default stuff... one for `Topic`
with the defaults... I'll clear my screen... and finally generate one for `User`.

Beautiful! The *only* thing this did was add three more CRUD controller classes.
But to make those useful, we need to link to them. I'll paste 3 more links...
then customize the label, font icons and class on each of them:

[[[ code('33834a5a0b') ]]]

Super fast!

Let's go check it out! Refresh and... look! Simply by running that command four
times, we now have four different fully-featured admin sections!

## The Main configure() Methods of your CRUD Controller

I want to look a little deeper into *how* this is working behind the scenes. Go to
`QuestionCrudController` and look at its base class:

[[[ code('2d0682c79d') ]]]

Hold `Cmd` or `Ctrl` to jump into `AbstractCrudController`. We saw earlier that our
dashboard extends `AbstractDashboardController`. CRUD controllers extend
`AbstractCrudController`.

Pretty much everything about how our CRUD controller works is going to be controlled
by overriding the configure methods that you see inside of here. We'll learn about
all of these as we go along. But on a high level, `configureCrud()` helps you
configure things about the CRUD section as a whole, `configureAssets()` allows you
to add custom CSS and JavaScript to the section, and `configureActions()` allows
you to control the *actions* you want, where an action is a button or link. So,
you can control whether or not you have delete, edit or index links on different
pages. More on that later.

The last super important method is `configureFields()`, which controls the fields
we see on both the index page *and* on the form. But don't worry about those too
much yet. We'll master each method along the way.

Below this, super cool... we can see the actual code that executes for each page! The
`index()` method is the *real* action for the index, or "list" page. `detail()`
is an action that shows the details of a single item, and `edit()` is the edit form.
I *love* that we can see the full code that runs all of this. It'll be
*super* useful when we're figuring out how to extend things.

But... wait a second. If you scroll back up to the configure methods, a few
of these look familiar. Some of these *also* exist in the dashboard base controller
class. And it turns out, understanding *why* some methods live in both classes
is the *key* to being able to make changes to your *entire* admin section *or*
changes to just *one* CRUD section. Let's dive into that next.

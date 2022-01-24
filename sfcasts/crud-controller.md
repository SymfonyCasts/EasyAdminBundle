# Hello CRUD Controller

The true reason to use EasyAdmin is for its CRUD controllers. Each CRUD controller
will give us a rich set of pages to create, read, update, and delete a single entity.
This is where EasyAdmin shines, and the next few minutes are going to be *critically*
important to understand how EasyAdmin works. So, buckle up!

## Generating the CRUD Controller

We have four entities. Let's generate a CRUD controller for `Question` first. Find
your terminal and run:

```
symfony console make:admin:crud
```

As you can see, it recognizes our four entities. I'll hit 1 for
`App\Entity\Question`, let this generate into the default directory... and with
default namespace.

Sweet! This did exactly *one* thing: it created a new `QuestionCrudController.php`
file. Let's go open that up.

## Linking to the CRUD Controller

But before we look too deeply into this, head over to the admin page and refresh
to see... absolutely no difference! We *do* have a new `QuestionCrudController`,
but these CRUD controllers are *totally* useless until we link to them from a
dashboard. So, back over in `DashboardController`, down at the bottom... `yield
MenuItem`... but instead of `linkToDashboard()`, there's a number of other things
that you can link to. We want `linkToCrud()`. Pass this a couple things: the label -
so `questions` - and an icon: `fa fa-question-circle`. That's a FontAwesome icon.
Then, most importantly, pass the entity's class name. So `Question::class`.

Behind the scenes, when we click this new link, EasyAdmin will recognize that
there is only *one* CRUD controller for this entity - `QuestionCrudController` -
and will know to use it. And yes, in theory, we *can* have multiple CRUD controllers
for a single entity. That's something we'll talk about later.

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
Symfony console make:admin:crud
```

This time generate a crud for `Answer`... with the defaults stuff... one for `Topic`
with the defaults... I'll clear my screen... and finally generate one for `User`.

Beautiful! The *only* thing this did was add three more CRUD controller classes.
But to make those useful, we need to link to them. I'll paste three more
links, then customize the label and font icons on each if them.

Let's check it out! Refresh and... look! By running the command four times, we now
have four different admin sections!

## The Main configure() Methods of your CRUD Controller

I want to look a little deeper into *how* this is working behind the scenes. Go to
`QuestionCrudController` and look at its base class. Hold "cmd" or "ctrl" to jump
into `AbstractCrudController`. We saw earlier that our dashboard extends an
`AbstractDashboardController`. CRUD controllers extend `AbstractCrudController`.

Pretty much everything about how our CRUD controller works is going to be controlled
by overriding the configure methods that you see inside of here. We'll learn about
all of these as we go along. But on a high level, `configureCrud()` helps you
configure things about the CRUD section as a whole, `configureAssets()` allows you
to control custom CSS and JavaScript in this section, and `configureActions` allows
you to control the *actions* you want, where an action is a button or link. So,
you can control whether or not you have a delete, edit or index link from different
pages. More on that later.

The last super important method is `configureFields()`, which is going to configure
which fields we see on both the index page *and* on the form. But don't worry about
those too much yet. We'll master all of this along the way.

Below this, we can actually see all of the code that executes each page! The
`index()` method is the actual action for the index, or "list" page. `detail()`
is an action that shows the details of a single item, and `edit()` is the edit form
I *love* that we can see the actual code that runs all of this. That will be
*super* useful when we're figuring out how to extend things.

But... wait a second. If you scroll back up to the configure methods, a few
of these look familiar. Some of these *also* exist in the dashboard base controller
class. And it turns out, understanding *why* some methods live in both classes
is the *key* to being able to make changes to your *entire* admin section *or*
changes to just *one* CRUD section. Let's dive into that next.

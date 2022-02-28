# The Dashboard Page

We know that, on a technical level, the dashboard is the key to everything. All Crud
controllers run in the context of the dashboard that link to them, which allows us
to control things on a global level by adding methods to the dashboard controller.

But the dashboard is also... just a page! A page with a controller that's the
homepage of our admin. And so, we can - and should - *do* something with that page!

The simplest option is just to redirect to a specific CRUD section... so
that when the user goes to `/admin`, they're immediately redirected to, for example,
the question admin. In a little while, we'll learn how to generate URLs to specific
Crud controllers.

*Or* to be a little more fun, we can render something *real* on this page. Let's
do that: let's render some stats and a chart.

To get the stats that I want to show, we need to query the database.
Specifically, we need to query from `QuestionRepository`.
`DashboardController` is a normal controller... which means that it's *also* a
service. And so, when a service needs access to *other* services, we use
dependency injection!

Add a constructor... then autowire `QuestionRepository $questionRepository`.
I'll hit Alt+Enter and go to initialize properties to create that property and set
it.

If you're wondering why I'm not using *action* injection - where we add the argument
to the method - I'll explain why in a few minutes. But it *is* possible.

Before we render a template, let's prepare a few variables: `$latestQuestions`
equals `$this->questionRepository->findLatest()`. That's a custom method
I added before we started. Also set `$topVoted` to
`$this->questionRepository->findTopVoted()`: *another* custom method.

Finally, at the bottom, like almost *any* other controller, return
`$this->render()` to render, how about, `admin/index.html.twig`. Pass in the
two variables: `latestQuestions` and `topVoted`.

Awesome! Let's go add that! In `templates/admin/`, create a new `index.html.twig`...
and I'll paste in the contents.

But there's nothing tricky here. I *am* extending `@EasyAdmin/page/content.html.twig`.
If you ever need to render a custom page... but one that still *looks* like it
lives inside the admin area, this is the template you want.

If you open it up... hmm, there's not much here! But check out the extends:
`ea.templatePath('layout')`. If you look in the `views/` directory of the bundle
itself, this is a fancy way of extending `layout.html.twig`. And *this* is
a great way to discover all of the different blocks that you can override.

Back in *our* template, the `main` block holds the content, we loop over the
latest questions... and the top voted. Very straightforward. And if you
refresh the page, instead of the EasyAdmin welcome message, we see our stuff!

## Adding a Chart!

Let's have some fun and render a chart on this page. To do this, we'll use a
Symfony UX library. At your terminal, run:

```terminal
composer require symfony/ux-chartjs
```

While that's installing, I'll go to the GitHub page for this library and load up
its documentation. These days, the docs live on symfony.com and you'll find a link
there from here.

Ok, so after installing the library, we need to run:

```terminal
yarn install --force
```

And then... sweet! Just like that, we have a new Stimulus controller that has the
ability to render a chart via Chart.js.

But I don't want talk too much about this chart library. Instead, we're going to
steal the example code from the docs. Notice that we need a service in order to
build a chart called `ChartBuilderInterface`. Add that as a second argument to
the controller: `ChartBuilderInterface $chartBuilder`. I'll hit Alt+Enter and go
to initialize properties to create that property and set it.

Then, all the way at the bottom... just to keep things clean... create a new private
function called `createChart()`... that will return a `Chart` object. *Now* steal
the example code from the docs - everything except for the render - paste it into
the method... and, at the bottom `return $chart`.

Oh, and `$chartBuilder` needs to be `$this->chartBuilder`. I'm not going to bother
making any of this dynamic: I just want to see that the chart *does* render.

Back up in the `index()` method, pass a new `chart` variable to the template set
to `$this->createChart()`.

*Finally*, to render this, over in `index.html.twig`, add one more `div` with
`class="col-12"`... and, inside, `render_chart(chart)`... where `render_chart()`
is a custom function that comes from the library that we just installed.

And... that should be it! Find your browser, refresh and... nothing! Um, force
refresh? Still nothing. In the console... a big error.

Ok, over in the terminal tab that holds Encore, it wants me to run
`yarn install --force`... which I already did. Hit Ctrl+C to stop Encore...
then restart it so that it sees the new files from the UX library:

```terminal-silent
yarn watch
```

And... yes! Build successful. And in the browser... we have a chart!

Next: let's do the *shortest* chapter ever where we talk about the pros, cons
and limitations of injecting services into the *action* methods of your admin
controllers versus through the constructor.

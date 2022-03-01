# Service Action Injection

You may have noticed that I seem to be *avoiding* "action" injection. For both
`QuestionRepository` and `ChartBuilderInterface`, normally, when I'm in a controller,
I'll like to be lazy and autowire them directly into the controller *method*.

## The Problem with Action Injection

Let's actually try that, at least for `ChartBuilderInterface`. Remove
`ChartBuilderInterface` from the constructor... and, instead add it to the method:
`ChartBuilderInterface $chartBuilder`.

And now... I need to pass `$chartBuilder` into `createChart()`... because, down here
we can't reference the property anymore. So add `ChartBuilderInterface $chartBuilder`...
and use that argument.

Cool. So in theory, this should work... because this is a normal controller and...
this is how action injection works! But you might already notice that PhpStorm is
pretty mad. And, it's right! If we refresh, huge error!

> `DashboardController::index` must be compatible with
> `AbstractDashboardController::index`.

The problem is that our parent class - `AbstractDashboardController` - has an `index()`
method with no arguments. So it's not *legal* for us to override that and add a
*required* argument.

## The Workaround

But if you *do* want action injection to work, there *is* a workaround: allow the
argument to be optional. So add ` = null`.

That makes PHP happy *and*, in practice, even though it's optional, Symfony *will*
pass the chart builder service. So this *will* work... but to code defensively
just in case, I'm going to add a little `assert()` function.

This may or may not be a function you're familiar with. It comes from PHP itself.
You put an expression inside like `null !== $chartBuilder` - and if that expression
is *false*, an exception will be thrown.

So now we can *confidently* know that *if* our code gets this far, we *do* have
a `ChartBuilderInterface` object.

Refresh now and... got it! So action injection *does* still work... but it's not
*as* awesome as it normally is. Though, it *does* have one concrete advantage
over constructor injection: the `ChartBuilderInterface` service won't be instantiated
unless the `index()` method is called. So if you were in a normal Crud controller
with multiple actions, action injection allows you to make sure that a service
is *only* instantiated for the action that *needs* it, instead of in *all* situations.

Next: let's learn how to override templates, like EasyAdmin's layout template, or
how an `IdField` is rendered across our entire admin area.

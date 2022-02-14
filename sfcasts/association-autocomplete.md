# Auto-complete Association Field & Controlling the Query

The `AssociationField` creates these pretty cool `select` elements. But these are
really just normal, boring `select` elements with a fancy UI. *All* of the options,
in this case, every user in the database, is loaded onto the page in the background
to build the `select`. This means that if you have even a hundred users in your
database, this page is going to start slowing down, and eventually, explode.

## AssociationField::autoComplete()

To fix this, head over and call a custom method on the `AssociationField` called
`->autocomplete()`:

[[[ code('2c55c950e3') ]]]

Yes, this *is* as nice as it sounds. Refresh. It *looks* the same, but when we type
in the search bar... and open the Network Tools... check it out! That made an AJAX
request! So instead of loading *all* of the options onto the page, it leverages an
AJAX endpoint that handles the autocompletion. Problem solved!

## Controlling the Autocomplete Items with formatValue()

And as you can see, it uses the `__toString()` method on `User` to display the
option, which is the same thing it does on the index page in the "Asked By"
column. We *can* control that, however. How? We already know: it's our old friend
`->formatValue()`. As you might remember, this takes a callback function as its
argument: `static function()` with `$value` as the *first* argument
and `Question` as the second:

[[[ code('568e743513') ]]]

The `$value` argument will be the formatted value that it's *about* to print onto
the page. And then `Question` is the current `Question` object. We'll eventually
need to make this argument `nullable` and I'll explain *why* later. But for now,
just pretend that we always have a `Question` to work with.

Inside: `if (!$question->getAskedBy())` - if for some reason that field is `null`,
we'll `return null`. If that *is* set, `return sprintf()` - with `%s`, `&nbsp;` for
a space, and then `%s` inside of parentheses. For the first wildcard, pass
`$user->getEmail()`.

Oh, whoops! In the if statement, I meant to say if `!$user =`. This, fancily, assigns
the `$user` variable *and* checks to see if there *is* an askedBy user all at once.
Finish the `->getEmail()` method and use `$user->getQuestions()->count()` for the
second wildcard:

[[[ code('69f7dbd321') ]]]

## HTML IS Allowed in EasyAdmin

Oh, and about that `&nbsp;`. I added this, in part, to show off the fact that when
you render things in EasyAdmin, you *can* include HTML in most situations. That's
normally *not* how Symfony and Twig work, but since we're never configuring
EasyAdmin *based* off of *user* input... and this is all just for an admin interface
anyways, EasyAdmin allows embedded HTML in most places.

Ok, let's check things out! Reload and... boom! We get our new "Asked By" format
on the index page.

The *real* reason I wanted us to do this was to point out that the formatted value
is used on the index page *and* the detail page... but it is *not* used on the form.
The form *always* uses the `__toString()` method from your entity.

## Controlling the Autocomplete Query

One of the things we *can* control for these association fields is the query that's
used for the results. Right now, our autocomplete field returns *any* user in
the database. Let's restrict this to only *enabled* users.

How? Once again, we can call a custom method on `AssociationField` called
`->setQueryBuilder()`. Pass this a `function()` with a `QueryBuilder $queryBuilder`
argument:

[[[ code('44b01e8577') ]]]

When EasyAdmin generates the list of results, it creates the query builder *for*
us, and then we can modify it. Say `$queryBuilder->andWhere()`. The only secret
is that you need to know that the entity *alias* in the query is always
`entity`. So: `entity.enabled = :enabled`, and then `->setParameter('enabled', true)`:

[[[ code('de61807928') ]]]

That's it! We don't need to *return* anything because we *modified* the
`QueryBuilder`. So let's go see if it worked!

Well, I don't think we'll *notice* any difference because I'm pretty sure every
user *is* enabled. But watch this. When I type... here's the AJAX request.
Open up the web debug toolbar... hover over the AJAX section and click to open
the profiler.

You're now looking at the profiler for the autocomplete AJAX call. Head over to
Doctrine section so we can see what that query looks like. Here it is. Click "View
formatted query". Cool! It looks on every field to see if it matches the `%ti%`
value *and* it has WHERE `enabled = ?` with a value of 1... which comes from up
here. Super cool!

Next: could we use an `AssociationField` to handle a *collection* relationship?
Like to edit the collection of *answers* related to a `Question`? Totally! But
we'll need a few Doctrine & form tricks to help us.

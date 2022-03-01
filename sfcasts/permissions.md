# Permissions

In `config/packages/security.yaml`, thanks to the `access_control` that we added
*way* back at the start of the tutorial, you can only get to the admin section
if you have `ROLE_ADMIN`. As far as security goes.... that's all we have so far. If
you have `ROLE_ADMIN`, you get access to *everything* inside of the admin area.
Lucky you!

But in this app, there needs to be *three* different admin user types and each will
have access to different *parts* of the admin section.

You can see these described up under `role_hierarchy`. We have `ROLE_ADMIN`,
which is the lowest level. Then we `ROLE_MODERATOR` above that, which *includes*
`ROLE_ADMIN`, but we're going to give this some *extra* access, like the
ability to moderate questions. And finally, there's `ROLE_SUPER_ADMIN`, which is
the highest level of permissions and will be allowed to do everything.

## Hiding a Menu Link by Role

Here's the first goal: only users with `ROLE_MODERATOR` should be allowed to go to
the Questions CRUD section. Right now, if I hover over the security part of the
web debug toolbar... yup! I *only* have `ROLE_ADMIN`... so I should *not* be
able to go here.

Fixing this is two steps. First, we need hide this link unless the user has
`ROLE_MODERATOR`. Open up `DashboardController`... and find
`configureMenuItems()`: this is where we configure those links. On the
`Questions` link, add `->setPermission()` and then pass the role that's required:
`ROLE_MODERATOR`.

Since the user *I'm* logged in as does *not* have this role... when we refresh, the
link disappears.

## The ?signature In the URL

But, of course, I still *technically* have access to this section! The link is gone,
but if someone sent me this URL, then I *could* still access this. So that *is*
still a problem. Though, at the very least, a user wouldn't be able to *guess* the
URL, because EasyAdmin generates a signature. That's this `signature="` part. What
that does is prevent anyone from messing with a URL and trying to access something
else. For example, if I tried to change "QuestionCrudController" to
"AnswerCrudController" to be sneaky and gain access to another section, I see:

> The signature of the URL is not valid.

So without the link to Questions, there won't be a way for me to somehow *guess*
the URL. *But* if somebody just sends me the link, I do still technically have access.
We'll fix that in a second.

By the way, if you want to disable that signature feature in your admin section,
that can be done in `configureDashboard()` by calling `->disableUrlSignatures()`.
Just be *extra* careful that you have your security configured correctly.

## Restricting a Crud Section By Role

Anyways, to *truly* restrict access to this CRUD section, go to
`QuestionCrudController`. In EasyAdmin language, what we need to do is set a
permission on the *action* or *actions* that should require that role. We don't have
a `configureActions()` method yet, so I'll go to "Override Methods" to add it.

What we've been doing so far is adding and disabling actions on certain pages.
We can *also* call `->setPermission()` and pass an action name - like `Action::INDEX`
and the role you need to have: `ROLE_MODERATOR`.

If I refresh the index page now... it fails!

> You don't have enough permissions to run the "index" action

Now go to the Homepage... log out... and log *back* in as
"moderatoradmin@example.com" with password "adminpass". Cool. This user has
`ROLE_MODERATOR`. Head back to the Admin section... and *now* we *do* see the
Questions link... and we can access the Questions section. Sweet!

*However*, we only restricted access to the *index* action. So the same problem
applies to the other actions: if someone sent me the URL to the "new" or "edit"
pages, then I *will* be able to access those... as long as I have the minimum
`ROLE_ADMIN`.

*So*, let's lock down a couple more actions: the `DETAIL` action for `ROLE_MODERATOR`
and also the `EDIT` action for `ROLE_MODERATOR`. In a few minutes, we'll learn
how to restrict access to an *entire* CRUD controller. What we're doing should
*only* be needed if you need to restrict things differently on an action-by-action
basis.

Ok, let's think. The only two actions that we haven't listed yet are `NEW` and
`DELETE`. Those are pretty sensitive, so I only want to allow super admins to
access those. Copy this, paste, and say `Action::NEW` restricted to
`ROLE_SUPER_ADMIN`. Paste again and say `Action::DELETE` *also* restricted to
`ROLE_SUPER_ADMIN`.

Thanks to these changes, when we refresh... yes! It hides the delete link correctly.
And even if I were able to guess the URL to that action, I wouldn't be able to get
there. Oh, but EasyAdmin has a really nice "batch delete"... and that *is* still
allowed. Let's lock that down as well.

Paste another line, change this to `BATCH_DELETE` with `ROLE_SUPER_ADMIN`. Now when
we refresh, the check boxes are gone! I have no batch actions that I can do on this
page.

Next, sometimes permissions are... *not* this complex! Let's learn how we can
restrict access to an *entire* crud section with one line of code.

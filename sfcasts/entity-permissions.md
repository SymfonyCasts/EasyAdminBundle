# Entity & Field Permissions

Most of the time, securing your admin will probably mean denying access to entire
sections *or* specific actions based on a role. *But* we can go a lot further than
that.

## Hiding a Field for some Admins

Let's imagine that, for some reason, the number of votes is a sensitive
number that should *only* be displayed and modified by super admins. Head over to
`QuestionCrudController`. This is something we can control on each field, so
find `VotesField`. Here it is. Add `->setPermission()` and then pass
`ROLE_SUPER_ADMIN`.

I'm currently logged in as "moderatoradmin", so I'm *not* a super admin. And so,
when I refresh, it's as simple as that! The votes field goes away, both on the list
page *and* on the edit page. Super cool!

## Hiding some Results for some Admins

Ok, let's try something different. What if we want to show only *some* items
in an admin section based on the user? Maybe, for some reason, my user can only see
*certain* questions.

Or, here's a better example. I'm currently logged in as a moderator, whose job is
to *approve* questions. If we click the Users section, a moderator *probably* shouldn't
be able to see and edit other user accounts. We could hide the section entirely for
moderators, *or* we could add some security so that only their *own* user account
is visible to them. This is called "entity permissions". It answers the question
of whether or not to show a specific *row* in an admin section based on the current
user. And we control this on the CRUD level: we set an entity permission for an
*entire* CRUD section.

Head over to `UserCrudController` and, at the bottom, override the
`configureCrud()` method. And now, for this entire CRUD, we can say
`->setEntityPermission()` and pass `ADMIN_USER_EDIT`.

Notice this is *not* a role. EasyAdmin calls the security system for *each* entity
that it's about to display and passes this `ADMIN_USER_EDIT` string into the security
system. If we used a role here - like `ROLE_SUPER_ADMIN` - that would return
true or false for *every* item. It would end up showing either *all* the items or
*none* of them.

Nope, a role won't work here. So, instead, I'm passing this `ADMIN_USER_EDIT` string,
which is something I *totally* just invented. In a few minutes, we're going to
create a custom voter to handle that.

But since we haven't created that voter yet, this will return false in the security
system in *all* cases. In other words, if this is working correctly, we won't see
*any* items in this list.

## Entity Permissions and formatValue()

Let's try it! Refresh and... okay. We don't see any items in the list, but it's
because we have a gigantic error. It's coming from `UserCrudController`: the
`formatValue()` callback on the avatar field:

> Argument #2 ($user) must be of type `App\Entity\User`, null given

And this is coming from a configurator. Go look at that field. Let's see... avatar...
here it is. You might remember that `formatValue` is the way we change how
a value is *rendered* on the index and detail pages. And it's really simple: it passes
us the current `User` objects - since we're in the `UserCrudController` and rendering
users - and then we return whatever value we want.

*But*, when you use entity permissions, it's possible that this `User` object will
be `null` because this is a row that *won't* be displayed. I'm not sure exactly *why*
EasyAdmin calls our callback... even though the row is about to be hidden, but it
*does*. So it means that we need to allow this to be `null`. I'll add a question
mark to make it nullabe.

And then, because we're using PHP 8, we can use a cool syntax here:
`$user?->getAvatarUrl()`. That says that *if* we're passed a user, call
`->getAvatarUrl` and return it. Else, just return null.

There's one other place that we need to do this. It's in `QuestionCrudController`,
down here on the `askedBy` field. Add a question mark, and then another
question mark right in the middle of `$question?->getAskedBy()`.

Go refresh again and... beautiful! No results are showing, and we get this nice
message:

> Some results can't be displayed because you don't have enough permissions.

Woo! And of course, if we tried to search for something, that would also take
into account our permissions.

Next, let's create the voter so that we can deny access *exactly* when we want to
and ultimately show only *our* user record when a moderator is in the Users section.

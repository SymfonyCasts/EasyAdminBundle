# Security Voter & Entity Permissions

Thanks to `->setEntityPermission()`, EasyAdmin now runs *every* entity in this list
through the security system, passing `ADMIN_USER_EDIT` for each. If we were running
this security check manually in a normal Symfony app, it would be the equivalent of
`$this->isGranted('ADMIN_USER_EDIT')`, where you pass the actual entity object -
the `$user` object - as the second argument.

Right now, when we do that, security always returns false because... I just invented
this `ADMIN_USER_EDIT` string. To run our custom security logic, we need a voter.

## Creating The Voter

Find your terminal and run:

```terminal
symfony console make:voter
```

I'll call it "AdminUserVoter". Perfect! Spin over and open this:
`src/Security/Voter/AdminUserVoter.php`. I'm not going to talk *too* deeply about
how voters work: we talk about those in our Symfony Security tutorial. But basically,
the `supports()` method will be called *every* time the security system is called.
The first argument will be something like `ROLE_ADMIN` or, in our case,
`ADMIN_USER_EDIT`. And *also*, in our case, `$subject` will be the `User` object.
Our job is to return true in that situation.

So let's check to see if the attribute is in an array with just `ADMIN_USER_EDIT`.
I don't really need `in_array()` anymore, but I'll keep it in case I add more
attributes later. Also check to make sure that `$subject` is an `instanceof User`.

That's it! Now, when the security system calls `supports()`, *if* we return true,
then Symfony will call `voteOnAttribute()`. Our job there is simply to return
true or false based on whether or not the current user should have access to
this `User` object in the admin.

Once again, we're passed the `$attribute`, which will be `ADMIN_USER_EDIT`, and
`$subject`, which will be the `User` object. To help my editor, add an extra "if"
statement: `if (!$subject instanceof User)`, then throw a new
`LogicException('Subject is not an instance of User?')`.

This should never happen, but that'll help my editor or static analysis. Finally,
down in the `switch` (we only have one `case` right now), if that attribute
is equal to `ADMIN_USER_EDIT`, then we want to allow access if `$user === $subject`.
So if the currently-authenticated `User` object - that's what this is here - is equal
to the `User` object that we're asking about for security, then grant access.
Otherwise, deny access.

Symfony will instantly know to use our voter thanks to auto configuration. So when
we refresh... got it! We *just* see our one user and the message:

> Some results can't be displayed because you don't have enough permissions.

Awesome! If you go down to the web debug toolbar, click the security icon, and then
click "Access Decision", this shows you all the security decisions that were made
during that request. It looks like `ADMIN_USER_EDIT` was called multiple times
for the multiple rows on the page. With this user object - access was denied...
and with this other user object - that's us - access was granted.

Entity permissions are also enforced when you go to the detail, edit, or delete
pages. Again, if you go down to the web debug toolbar and click "Access Decision",
at the bottom... you can see it checked for `ADMIN_USER_EDIT`.

## Granting Access to ROLE_SUPER_ADMIN

This is great! Except that super admins should be able to see *all* users. Right
now, no matter who I log in as, we're only going to show *my* user. To solve this,
down in our logic, we can check to see if the user has `ROLE_SUPER_ADMIN`. But
to do *that*, we need a service.

Add `public function __construct()`, and inject the `Security` service from Symfony
(I'll call it `$security`). Hit "alt" + "enter", and go to "Initialize properties"
to create that property and set it. Then, down here, return true if
`$user === $subject` *or* if `$this->security->isGranted('ROLE_SUPER_ADMIN')`.

Cool! I won't bother logging in as a super admin to try this. But if we *did*,
we would now see *every* user.

## Adding Permissions Logic to the Query

So there's just one *tiny* problem with our setup. Imagine that we have a lot of
users - like *thousands* - which is pretty realistic. And *our* user is ID 500. In
that case, you would actually see *many* pages of results here. And our user *might*
be on page 200. So you'd see no results on page one... or two... or three... until
finally, on page 200, you'd find our *one* result. So it can get a little weird if
you have *many* items in an admin section, and *many* of them are hidden.

To fix this, we can modify the query that's made for the index page to *only* return
the users we want. This is totally optional, but can make for a better user
experience.

So far, we've been letting EasyAdmin query for *every* user or *every* question.
But we *do* have control over that query. Open up `UserCrudController` and, anywhere,
I'll go near the top, override a method from the base controller called
`createIndexQueryBuilder()`.

Here's how this works: the parent method starts the query builder for us. And it
already takes into account things like the Search on top or "filters", which we'll
talk about in a few minutes.

Instead of returning this query builder, set it to `$queryBuilder`. Then, because
super admins should be able see *everything*, if
`$this->isGranted('ROLE_SUPER_ADMIN')`, then just return the unmodified `$queryBuilder`
so that *all* results are shown.

But if we *don't* have `ROLE_SUPER_ADMIN`, that's where we want to change things.
Add `$queryBuilder->andWhere()`. Inside the query, the alias for the entity
will always be called "entity". So we can say `entity.id = :id` and
`->setParameter('id', $this->getUser()->getId())`. I don't get the auto complete
on this because it thinks my user is just a `UserInterface`, but we know this *will*
be our `User` entity which *does* have a `getId()` method. At the bottom,
`return $queryBuilder`. And... I guess I could have just returned that right here...
so let's do that.

I love it! Let's try it! Spin over and... nice! Just our *one* result. And you don't
see that message about results being hidden due to security... because,
*technically*, *none* of them were hidden due to security. They were hidden due
to our query. But regardless, permissions are *still* being enforced. If a user
somehow got the edit URL to a User that they're not supposed to be able to access,
the entity permissions will *still* deny that.

Next, each CRUD section has a nice search box on top. Yay! But EasyAdmin *also* has
a great filter system where you can add more ways to slice and dice the data in each
section. Let's explore those.

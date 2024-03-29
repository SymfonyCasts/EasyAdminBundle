## Restricting Access to an Entire Crud Section

So... great! We can now restrict things on an action-by-action basis. But
*sometimes*... it's not that complicated! *Sometimes* you just want to say:

> I want to require `ROLE_MODERATOR` to be able to access *any* part of a
> CRUD section as a whole.

In that case, instead of trying to set permissions on *every* action like this, you
can be lazy and use *normal* security.

For example, head to the top of `QuestionCrudController`. Above the class,
leverage the `#[IsGranted]` attribute from SensioFrameworkExtraBundle. Just for a
minute, let's pretend that we're going to require `ROLE_SUPER_ADMIN` to use *any*
part of this section.

[[[ code('9df3db3bd4') ]]]

If we move over now and refresh... "Access Denied"! Yea, since these controllers
are *real* controllers, just about everything that works in a normal controller
also works inside of these CRUD controllers.

Let's undo that. *Or*, if you want, we can put `ROLE_MODERATOR` up there to make
sure that if we missed any actions, users will *at least* need to have
`ROLE_MODERATOR`. Since we're already logged in with that user, now...
we're good!

[[[ code('a41c8d14d4') ]]]

## Make sure Permissions Match Link Permissions

One thing I *do* want to point out is that, when you link to something, you *do*
need to keep the permissions on that link "in sync" with the permissions
for the controller you're linking to.

For example, let's temporarily remove the link permission for the menu item.

[[[ code('7211ca9cc7') ]]]

Then, in `QuestionCrudController`, down on index, temporarily require
`ROLE_SUPER_ADMIN`. This means that we should *not* have access.

[[[ code('036fb11eb2') ]]]

And if we move over and refresh... that's true! We're denied access! But go
back to `/admin`. Uh oh: the Questions link *does* show up. EasyAdmin isn't smart
enough to realize that if we clicked this, we wouldn't have access. It's *our*
responsibility to make sure that the permissions on our link are set up correctly.

Go change this back to `ROLE_MODERATOR`... and over here, we'll restore that
permission. Now we're good. Our question section requires `ROLE_MODERATOR` and
specific actions inside of it, like `DELETE`, require `ROLE_SUPER_ADMIN`.

Nice work team!

But security can go even further! Next let's hide individual fields based on
permissions and even hide specific entity *results* based on which admin user
is logged in. Whoa...

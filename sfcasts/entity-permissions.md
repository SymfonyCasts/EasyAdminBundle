# Entity Permissions

Most of the time, securing your admin will probably mean denying access to entire sections *or* specific actions based on the role. *But* we can go a lot further than this. Let's imagine that, for some reason, the number of votes here is a sensitive number that should only be displayed and modified by super admins. Head over to `QuestionCrudController.php`. This is something we can control in the fields, so let's find `VotesField`. There it is. We can call `->setPermission()` and then pass `ROLE_SUPER_ADMIN`. I'm currently logged in as "moderatoradmin", so I'm *not* a super admin. And when I refresh, it's as simple as that. The votes field goes away, both on the list page *and* on the edit page. Super cool!

All right, let's try something different. What if we want to show only *some* items on an admin based on the user? Maybe for some reason, my user can only see *certain* questions. Or, to give another example, currently we're logged in as a moderator, whose job is to approve questions. If we click the Users section, a moderator *probably* shouldn't be able to see and edit other user accounts. We could hide the section entirely from moderators, *or* we could add some security so that only their *own* user account is visible to them. This is called "entity permissions". It answers the question of whether or not to show a specific row in an admin section based on the current user, and we control this on the CRUD level. We set an entity permission for an *entire* CRUD section.

This means that we'll go to `UserCrudController.php` and, at the bottom, I'll override the `configureCrud` method. And on the entire CRUD, we can say `->setEntityPermission()`. Now pass this `ADMIN_USER_EDIT`. You'll notice this is *not* a role. EasyAdmin calls the security system for each entity it's about to display and passes this `ADMIN_USER_EDIT` string into the security system. If I were to use a role here, like `ROLE_SUPER_ADMIN`, that would return true or false for *every* item in a section, and it would end up showing either *all* the items or *none* of them. Instead, I've passed this `ADMIN_USER_EDIT` string, which is something I totally just invented. And in a few minutes, we're going to create a custom voter to handle this. But since we haven't created that voter yet, this will return false in the security system in *all* cases. In other words, if this is working correctly, we won't see *any* items in this list.

Let's try it! Refresh and... okay. We don't see any items in the list, but it's because we have a gigantic error. It's coming from `UserCrudController.php`, on our `formatValue` callback on our avatar field and says

> Argument #2 ($user) must be of type App/Entity/User

because of our type hint

>null given

And this is actually coming from a configurator. Go look at that field. Let's see here... avatar... here it is. You might remember that `formatValue` is the way for you to change how a value is rendered on the index and details pages, and it's really simple. It passes us the current User objects since we're in the `UserCrudController.php` and then, we can return whatever value we want. *But*, when you use entity permissions, it's possible that this User object will be null because this is a row that won't be displayed. I'm not sure exactly *why* EasyAdmin calls our callback, even though the row is about to be hidden, but it *does*, so it means that we need to allow this to be null. I'll add a question mark to allow it to be null.

And then, because we're using PHP 8, we can use a cool syntax here: `$User?->getAvatarUrl()`. That says that *if* we're passed a user, call `->getAvatarUrl` and return it. Else, just return null. There's one other place that we need to do this. It's in `QuestionCrudController.php`, down here on the `->getAskedBy()` field. Add a question mark, and then another question mark right in the middle of `$question->getAskedBy()`.

Go refresh again and... beautiful! No results are showing, and we get this nice message:

> Some results can't be displayed because you don't have enough
> permissions

Super nice! And of course, if we tried to search for something, that would also take into account our permissions.

Next, let's create the voter so that we can deny access *exactly* when we want to, and ultimately show only *our* user record when we're inside of the Users section.

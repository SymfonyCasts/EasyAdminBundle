# Permissions

Coming soon...

In config, packages, security dot Yael, thanks to the access control that we added
way back at the beginning of the tutorial, you can only get to the admin section if
you have role_admin, but as far as security goes, that's all we've done. If you have
role admin, you have access to do everything inside of the admin area. But in
reality, in this application, we have three different user types. You can see them
described up here under roll hierarchy. We have roll admin, which is the lowest
level. Then we have roll moderator above that <affirmative> which includes role
admin, but we're gonna give this some special permissions, like the ability to
moderate questions, and finally there's role super admin, which is the highest and is
allowed to do everything.

So for example, only moderators only users with role moderator should be allowed to
go to the questions crud section. So right now I am actually rolled, logged in. If I
hover over this with role_admin, see that, and I do currently have access to this to
fix that. The first thing we wanna do is hide this link unless I have roll moderator.
So let's go over to our dashboard controller. That's where we can configure our
links, uh, up to configure menu items. Here's the questions we're on right here. And
we can just call on this->set permission, and then past this, the permission to
check, which for us is role_moderator. Now, as I mentioned, the user I'm logged in as
right now, does not have this role. So not refresh the link disappears, but check it
out. I still technically have access to the questions section.

I just don't have. I just doesn't show the link anymore. So if somebody sent me this
URL, then I could still access this, but I wouldn't ever be able to guess this URL
because one of the things I haven't talked about is that easy admin generates a
signature on this URL. You can actually see it down here. The signature = what that
does, is it prevents anybody from messing with a URL and trying to access something
else. Like if I did, if I tried to change question credit controller to answer credit
controller, because I'm trying to change the URL to get access somewhere else, I get
the signature of the URL as not valid. So without the link to questions, there's not
gonna be a way for me to somehow guess what that is by changing the URL here and
getting there. But if somebody just links me directly there, I do still technically
have access. And we'll fix that in a second. But by the way, if you wanna disable
that signature feature in your a for some reason That can be done in configured
dashboard, by calling disabled URL signatures Anyways, to truly restrict access to
this crud section, let's go to question crud controller.

And what we're gonna do is over is configure our actions. So I don't think I have
configure actions in here yet. So I'll go to override methods, override configure
actions in. Normally what we've been doing so far is we've been maybe like adding
Actions to certain pages or disabling actions. One of the other things you can do is
call set permission and here you put an action name. So I'll use the action class, an
index. So on the index page, you need to have role_moderator. And now if I refresh
the index page, it fails. You don't have enough permissions to run the index action.
All right. So let me kind of go back here. I'm gonna log out. I'm go to my home on
page, actually log out and I'll log back in as moderator admin, at example.com
password Admin pass. Beautiful. I'll head back to my admin section and now I do have
the questions link and I can access the questions section sweet, but we only
restricted access to the index action. So same thing. If somebody's able to guess
that URL to the new page or the edit page, they're going to be able to get there.
<affirmative>

Okay.

So let's lock down a couple more of those let's lock down the detail page for roll
moderator and also the edit, the edit action for roll modifier. And by the way, I'll
in a few minutes, I'm gonna show you how to restrict access to an entire crud
controller. This is only needed if different users have access to your correct
controller, but you need to restrict on a action by action basis. All right. So let's
think about it. The only two pages that I haven't listened here yet are the new PA
the new action and the delete action, and those are kind of sensitive. So I wanna
only allow super admins to be able to access those actions. So let me copy this and
we'll say, action, new it's gonna be roll super admin. And then action. Delete will
also be role super admin. And there is also a batch delete. So you could probably
probably want to, um, restrict that one as well. If you really want this to be locked
down, grow

As a result of this one, refresh, yes, you can see the delete action, the delete even
highs, the delete links correctly. You even if, I guess that you were all, I wouldn't
be able to get there. Now I saw a second ago that the batch delete was still allowed
as if I checked these, I can still hit that would still be allowed. Let's go ahead
and lock that down as well. Batch delete, roll super admin. Now I refresh, you can
see the check boxes are gone, cause I have no batch actions that I can do on this
page. So this is how you can restrict things on an action by action basis. But
sometimes it's not this complicated. Sometimes you just wanna say, look, I wanna
require a role like role moderator to be able to access any entire crud section as a
whole. So in that case, instead of trying to set the permission on every single
action like this, you can skip this part instead, just use normal security. So first
we've all

So far as example, let's head up to the top of question, product controller, and I'm
gonna leverage the, is granted from Sensio framework, extra bundle. And just for a
second, let's pretend that we're gonna require role super admin to get to anywhere
under this Cru question, crash controller. And we head over now and refresh we get
access denied. So just keep in mind that these controllers are real controllers. So
pretty much everything you can do in a normal controller, you can do inside of these
crud controllers as well. All right, but let me undo that. Or if you want, I'll put
role moderator up there just to make sure if I missed any actions you at least need
to have role moderator to get to them. Since I am logged in with that user now I'm
good.

Now, one thing I do wanna point out here is that you do need to keep your security
when you link to something in line with the security that you should require on your
controller. For example, let's temporarily remove the link permission down here. And
then in question crud controller down on the index, I'm temporarily gonna require
roll super admin. So this means that I currently should not have access to the index
page. And if we go over here and refresh, that's true, I am denied access, but if I
just go back to /admin here, you can see the question link does show up so easy
admin. Isn't smart enough to realize that if we go like this, we're not gonna have
access. We need to make sure that we keep that in sync ourselves. So I'm gonna go
back and undo that role moderator. And over here, we'll go and put that permission
back and we are now good. Our question section now requires role moderator and
specific actions inside of it, like delete require role, super adamant, pretty sweet.
Next security can go further where we even hide individual fields based on the, on
security or even hiding or showing specific entities like showing this entity and
this entity, but not that entity to based on which admin user is logged in. Whoa,


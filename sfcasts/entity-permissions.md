# Entity Permissions

Coming soon...

Most of the time securing your admin will probably mean denying access to entire
sections Or specific actions based on the role. But we can go a lot further than
this. Let's imagine that for some reason, the number of votes here, this is a
sensitive number that should only be displayed and modified by super admins. Head
over two question credit controller. This is something we can control in the fields.
So let's find votes. There it is. We can call set permission and then pass, roll
super admin. I'm currently logged in as Moderat admin, so I'm not a super admin. And
so when I refresh it's as simple as that, the votes field goes away, both on the list
page and also on the edit page. Super cool. All right. So let's try something
different. What if we wanna show only some it in an admin based on the user, like
maybe for some reason, my user can only see certain questions or to give another
example. Currently we're logged in as a moderator, whose job is to approve questions.

<affirmative>

If we click the user section a moderator, probably shouldn't be able to see and edit
other user accounts. We could hide the section entirely from moderators, or we could
add some security so that only their own user account is visible to them. This is
called entity permissions. It answers the question of whether or not to show a
specific row in an admin section based on the current U user. And we control this on
the crud level, we set an entity permission for an entire crud section. That means
that we'll go to user crud controller and at the bottom I'll override the configure
crud method.

And on the entire quad, we can say set entity permission. And I want you to pass this
admin user edit notice. This is not a role, easy admin calls, the security system for
each entity. It's about to display and passes this admin user edit string into the
security system. So if I were to use a role here, like role super admin, that would
return true or false for every item in a section, and it would end up showing either
all the items or none of the items. So instead I've passed this admin user edit
string, which is something I totally just invented. And in a few minutes, we are
going to create a custom voter to handle this. But since we have not created that
voter, yet, this will return false in the security system in all cases.

In other words, if this is working correctly, we won't see any items in this list. So
let's try it refresh and okay. We don't see any items in the list, but it's because
we have a gigantic air. It's interesting. It's coming from user crud controller on
our format value callback on our avatar field and says an argument two user must be
of type user because of our type end, no given. And this is actually coming from a
configurator, which is kind of cool. So let's go look at that fee. Let's see here,
avatar here it is right here. So we might remember that format value is the way for
you to change how a value is rendered on the index and details page. And it's really
simple. It passes us the current user objects since we're in the user crowd
controller. Um, and then we can return what or value we want. But when you use entity
permissions, it's possible that this user object will be null because this is a row
that's about that's that won't be displayed. I'm not sure exactly why easy admin
calls for our callback, even though the row is about to be hidden, but it, so it
means that we need to allow this to be null. So I'm gonna add question mark to allow
it to be null.

And then because we're using pH P eight, we can use a nice little cool syntax here.
User question, mark arrow, get avatar URL. So that says that if user is, if we're
past a user call, get avatar URL and return it else just return null. And there's one
other place that we need to do this. It's in question credit controller down here on
the ask buy field. So I'll add question mark, And then question mark, right in the
middle of question arrow, get asked by all right, refresh now and beautiful. No
results are showing, get this nice message. Some results can't be displayed because
you don't have enough permissions, which is super nice. And of course we did this
search that would also, um, take into account our permissions. So next let's create
the voter so that we can deny access exactly when we want to And ultimately show only
our own user record when we're inside of the user section.


# Voter

Coming soon...

Thanks to set ND permission, easy admin now runs every entity in this list through
the security system, passing admin user edit for each one. If we are running this
manually in a normal Symfony application, this would be the equivalent of running.
Basically asking this->is granted admin user edit, where you pass the actual entity
object, the user object as the second argument right now, when we do that, security
always returns false because I just invented this admin user edit string to run our
custom security logic. We need a voter, find your terminal and run Symfony console,
make voter.

I'm gonna call it admin user and voter. Perfect. Then we'll spin over and go to
source security voter admin user voter. I'm not gonna talk too deeply about how
voters work. We talk about those in our Symfony security tutorial, but basically the
supports method here is gonna be passed. Admin user edit, and then this is going to
be the user object in our situation. We want to return. We want to return true in
that situation. That's confusing. So what I'll do here is I'll check to see if the
attribute is in an array with just admin user edit.

I don't really need in array here anymore, but I'll keep it in case I add more
attributes later and they'll check to make sure that the subject is an instance of
user. Perfect. So if we return true from this method, and if the security system
calls us calls supports, and we return true from this method, we know that we are
trying to decide or vote on our situation. In that case, the security system calls
vote on attribute and we simply need to return true or false based on whether or not
the current user should have access to view this user object in the admin.

Once again, we're past the attribute, which will be admin user edit and this subject,
which will be the user object to help my editor. I'm gonna add a little extra if
statement here, I'm gonna say, if not subject is an instance of user, then throw a
new exception. This should never happen. That'll help my editor in any stack
analysis. So finally, down here in the switch case, we only have one case right now,
if that attribute is equal to admin user edit, then we want to allow access if user
is equal to subject. So if the current user object, that's what this user is. The
currently authenticated user object is equal to the user object that we're currently
asking about for security, then grant access, otherwise deny access simp. You will
instantly know to use our voter thanks to auto configuration. So when we refresh, got
it, we see just our one user and the awesome, some results cannot be displayed
because you don't have enough permissions. Really cool. If you go down to the web web
toolbar and click this security icon at the let's see, and then click access
decision. This shows you all the security decisions that were made in that request.

And if you look admin user edit was called multiple times. It was passed with this
user access was denied. It was passed with this user that's us access was granted. It
was passed with a different user access denied. So you can see in, in real time those
security decisions being made. Now the security system, the entity permissions is
also enforced when you go to a, the details page or the edit page or the delete page.
Once again, if you go down to be the web deal of our toolbar, whoops, and click
access decision, then down here, there it is. You can see it was checking for admin
user edit.

So this is awesome, except that super admin users should be able to see all users
right now, no matter who I log in, as we're only gonna show that user. So to solve
this down in our logic, we can just check to see if the user has roles super admin in
order to do that, we're going to need a service. So I'll add public
function,_underscore construct, and the security service from Symfony call security,
call the alt enter, go to initialized properties to create that property and set it.
And then down here we return true. If user = = = subject, or if this->security->is
granted role super admin.

Cool. So I'm not gonna bother logging in as a super admin to try this. You can, but
if we did do that, we would see every user now. Okay. So there's just one kind of
teeny tiny problem with that. If we had a lot of users in our data database, which is
pretty realistic, then imagine we had a lot of users in our database like thousands,
which is pretty realistic. And imagine that our user is ID 500. In that case, you
would actually see many pages of results here. And our user might not be, might be on
page 200. And so you'd see as you'd see no results and on page two, no results and on
page three, no results until finally on page 200, you saw our one results. So if we
get a little bit weird, if you have many items in an admin section, and many of them
are hidden.

So to fix this, we can modify the query that's made for the index page to only return
the users we want. This is totally optional, but can make for a better user
experience. We haven't actually done this yet forward. We're just letting easy admin
just query for every user or every question, but we actually can have full control
over that query to do that, go into our user crowd controller. And it doesn't matter
where I'll go near the top. I'm going to override a method from the base roller
called create index query builder, super powerful, being able to override these
methods. So what happens here is the parent class starts the query builder for us.
And it already takes into account things like, um, the, like the search on, on top or
the filters that we're gonna talk about in a few minutes.

So instead of returning that, I'll say query builder equals. And then remember if
we're a super admin, we can see everything. So we're gonna say, if this->is granted
role super admin, then just return that query builder, which is going to return every
result. But if we don't have role super admin, that's where we wanna modify things.
So we can say query builder, and then, and where then inside the query, the alias for
our entity is going to be called entity. So we can say.id = colon ID and set
parameter ID set to this arrow, get user arrow, get ID, get ID. I don't get that auto
completed because it thinks my user is just a user interface, but we know this is our
user entity. We know it has a good ID method, so that will work. And at the bottom
we'll return query builder. And I guess I could have just returned right here. Let's
actually do that.

Beautiful. All right, let's try it spin over and nice. Just our one results. And you
don't see that message anymore about results being hidden due to security that's
because technically none of them were hidden due to security. They were just hidden
due to our query, but then thing is permissions are still being enforced. So if a
user somehow got the edit URL to something they're not supposed to be able to see the
entity permissions are still going to deny that. All right, next, we know that each
crowd section has a nice search box up here. Yay. But easy admin also has a nice
filter system where you can add more ways to slice and dice the data in each section.


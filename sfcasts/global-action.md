# Global Action

Coming soon...

There are actually three different types of actions In easy admins. There are the
normal actions like add, uh, edit, delete detail. That's the first, the second are
batch actions. The normal actions operate on a single S entity. Second batch actions
operate on a set selection of entities. So I can collect two of these check boxes and
I have a delete button up here that is the batch delete. And it's the only builtin
batch action

And side note on that to get things to work properly, you should probably remove the
batch delete for the question. Otherwise, people are gonna try to batch delete
questions, which won't work. Thanks to our code. They will get a 500 error. Anyways,
the third type of action is called a global action, which operates on all the
entities in the section. There are no built in global actions, but we're gonna add
one to export this question list to a CSV file. For the most part, creating a batch
or a global action. Isn't my much different than a normal custom action. It starts
the same way over actions. Config I'll create a new export action = action. New I'll
call it export. Then this time I'm going to link to credit action. We'll call it
export. Then I'll add some CSS classes. And in icon Below, let's add this to our
index page, which is the only page that, uh, which is the page where it makes most
sense to add a global action. Beautiful. Now, if we stop now, this is actually just a
normal action. If I refresh it is showing up next to each item over here, which is
not what we want to make it a global action back up in action.

We'll call create ads global action. You can also see how you would create a batch
action

Now in refresh. Awesome. It's up on top. So I need to correct that new. It's probably
a global action. If you click this, we get an error because we haven't made that
action to export, to CSV. We're going to install a third party library to help at
your command line. Come around. Compos require hand crafted in the Alps. How's that
for a great name /goodbye. CSV good by CSV is a well known CSV package, but it hasn't
been updated for a while. So handcrafted in the Alps has forked that and made it work
with modern versions of PHP. Awesome. Also, if you downloaded the course code, you
should have a tutorial directory with a CSV exporter inside. Copy that. And then in
your source service directory paste, this is gonna handle the heavy lifting of
exporting the CSV for us.

See at the bottom, this actually will return a streamed response that a Symfony
response, but the response will actually contain a five download that contains the
CSV. So I'm not gonna go into the specifics of how this works now to call this
method, we need to pass it two things, the query builder, three things that should be
used to query for the results, the field collection that comes from easy admin, it's
the fields to include, and also the file name that we want to use for the file
download. All right. So in question credit controller, let's create that export
export action. Now, just to mention, if you want, you could simply inject the
question repository here, do whatever query you want to return the questions and pass
that through the CSV, um, system. But we are gonna do something a bit more complex
when we click this export button.

I, I wanted to export exactly what we see in this list, including if we have things
ordered a certain way, or if we have a search parameter up here to do that, we need
to steal some code from our parent class. So I'm gonna scroll to the top of this
class and then hold man to open abstract crud controller search inside here for
index. There it is. So index is what actually renders the list page. And what's
useful is we can see a little bit of logic about how it makes its query, and we wanna
replicate that. So the key thing we now need down here are these three fields. This
is where it figures out which fields to show, which filters are being applied and
ultimately it creates the query builder. So let's copy that. Go back down into our
export action and paste. I'll say, okay, to add a couple of you statements now to get
this to work, we need a context. That's the admin context, which as you probably
remember, is something that we can auto wire as a cert into our methods. Well, I'll
say admin context and call it this time context. Awesome. So at this point we have
the query builder and the field collection that we need to call our CSP exporter.

So let's do it auto wire that CSV exporter into here And then at the bottom should be
as simple as return CSV, exporter, arrow, create response from query builder. And
then we're gonna pass that the three arguments, which is the query builder, The
fields, and then some file name. So I'm just gonna say questions CSV. All right,
let's try it. I'll refresh, hit export and looks like it worked. Let me open that up.
Beautiful. We have a CS of all of our data, but there is kind of one little hidden
thing here. That's not working. Notice the ordering of these. It's just in some
random order, Sorry, don't show this part. But if we look, I had things ordered by ID
and actually let's search for something. So this should only be seven results if we
export that and open it. Yep. We get these same bunch of results. So the search is
not working earlier either. So the problem is that when we click this export button,

<affirmative>

On this page, the all search term and any ordering we have is reflected in the URL
via query parameters. But when we press the export button, we get the basic query
parameters like which crud controller and which action we ring called, but any
filtering or searching or ordering those query parameters are not included on there.
And so when we call these builder and filter things here, the parameters, aren't
there to tell it to do the filtering or ordering. So to do that, we're actually going
to generate a smarter URL here that does include those parameters. So the way we're
gonna do this is up in our configure actions Instead of link to crud action, we're
gonna say link to URL and just completely take control of our ourselves. So I'll pass
this a call back

Then inside of here, we're generate the URL manually. And you might remember that we
to generate, uh, URLs to easy admin, we need the admin URL generator. So right now
I'm inside of configure actions. That's not actually a method that can auto wire
services into this is not a real action. So I'm going to get these service is by
adding a instructor. So let's say public function,_construct and let's auto wire, the
admin URL generator, and also the request stack. You'll see that we're gonna need
that in a second to get the request object I'll hit alt enter and go to initialize
properties to create both of those properties and set them back down inside of
configure actions. Here we go. Inside link to URL First, get the request request =
this->request, sta->get current request Then for the URL, we'll just create it from
scratch this->admin URL, generator,->set, all request, arrow, query arrow, all. So
what that does is it starts generating a URL that has all the same query parameters
as the current request. Then

We'll just override a couple things. We'll set the action to export and then say,
generate URL. So generate me the same URL that I have now, but change the export to
point to our, the action to point to our new export. All right, so let's try this
now. I'll just refresh this. I'll refresh this page that the <affirmative> new UR URL
generates. And as I mentioned, we should have about seven results export, open that
up and yes, we've got, it shows up the same results here in the same order that we
see over here. All right, next, let's learn to reorder the actions themselves and
generate a URL from our front end front end show page. So we can have a button right
here that links directly the edit action of this entity in our admin.


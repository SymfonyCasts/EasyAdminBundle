# Association Many

Coming soon...

The association field creates these pretty cool select elements here, but these are
really just select normal, boring select elements with a fancy UI, all of the
options. So in this case, every user in the database is loaded onto this page in the
background To build a select element. This means that if you even have a hundred
users in your database, this page is going to start getting really slow. And
eventually it's gonna break To fix this. We can head over and call a custom method on
the association appeal called autocomplete. Ugh. So nice try. Now, when we refresh,
It looks the same, but if I type and go to network tools, check this out, that
actually made an O that made an a X request when I was typing. So instead of loading
all the options on the page, it leverages an a X endpoint to handle this auto
completion. So problem solved. And as you can see, it's using our two string method
on user to, um, display what's here,

Which is the same thing it's doing on the question list page. You can see the asked
by it's using the two stream method that we used on user. However, we can control
that. And I just wanna point this out. This is something we did before we can control
it with format value, which if you remember, what we do is we pass it a callback
function with value you as the first argument question as the second argument. So
value here is gonna be the format of value that it would, that it's about to print
onto the page. And then question here is our current question object again, later,
I'm gonna tell you that we actually need to make this nullable. I'll explain why, but
for now we can just, uh, pretend that we always have a question object here to work
with, which makes sense Down here. I'll just put a little if statement. So if not,
question->will get asked by for some reason that, uh, field is Noll or return will
return. No, L will return a little string here as I present S and MBSP for a space
and then percent S inside of parentheses and PHY op ask user arrow, get email.

Oh, and actually what I meant to do is say not user equal, so I'm kind of getting
fancy and assigning the user variable and checking all online. Perfect user email,
email, and I can say user arrow, get questions,->count. So we get a nice little, uh,
thing here. Now notice I have the NVS P uh, I'm doing that in part to show off that
most of the time, an easy admin, when you render things, you can include HTML.
<affirmative>, that's normally not how the web works, but since this is for an admin
interface, and we know that we're in control, a lot of times, easy admin allows you
to embed HTL right into these strings, and it renders them just fine. So when I
refresh boom, there we go. We get our nice ask by now. One of the reasons I show this
is just to point out that the form format value is used on the list page. And it's
also used on the details page, but it's not used on the form. The form always uses
the two string on your entity. So just wanted to point that out. One of the things we
can control, however, on these association fields is the query that's used for the
results. So right now our auto complete is gonna be returning any user in the entire
database. A lot of times you might want to restrict it to only a subset to do that.
We can once again, call another custom method on the association field called set
query builder. This is going to accept a function with a query builder argument.

And so what's gonna happen is it's going to kind of create the query builder for you,
and then you can modify it. So I'll say query builder and where, and then the only
thing that, that you need to know about is that the entity alias is always called
entity. So it's always entity dot enabled. So we'll do here, as I'll say, entity dot
enabled = colon enabled, then I'll say set parameter enabled. True. So we'll filter
out users that aren't not enabled.

Perfect. And I don't need to return anything. This modifies, the query builder
<affirmative> to see if that works well, we don't really notice any difference, cause
I think all of our users are enabled, but watch this out. I'm gonna type here's the
Aja request for that right there. And if I open up my web deal tu bar, you'll see
here's the profiler for the Aja request. So I'm gonna hold and open this in a new
window. So you're now looking at the profiler for that auto complete ax call. And I
can head over to doctrine. We can kind of verify what that query looks like here it
is down here. Perfect. So let me hit view formatted query, and you see, it's
basically looking on every single field to see if it matches our, Our T percent ti
percent and enabled equals, uh, one there's the value up there. So pretty cool. All
right. There's one other field association field that I want to include on this crud.
And it is an interesting one it's answers. So unlike top and answered by this is a
collection. Each question has many answers. So back in our credit controller, let's
add yield association, field new answers, and let's just kind of see what we have ha
have here. So I'm gonna click back to the index page and awesome by default, it
recognizes that that it's a collection. So it prints the number of answers that each
has, which is pretty so wheat. And if we go to a form, we get a very familiar air.
The form is once again, trying to get a string representation of our entity.

So we know what to do. We need to go and answer, and we can add the two string
method, but there is actually one other way to handle this. If you're familiar with
these Symfony form components, then this problem of converting your entity into a
string is something that you see all the time with the entity type and the two ways
to solve it are either to add the two string method to your entity, or you can pass
your form field, a choice label option. And we can do that here, because remember
There is a set form type option method that we can pass here.

So actually before I fill that in, let me open up the association field here, and I'm
gonna scroll down to new. So you can see behind the scenes, this is actually using
the entity type. So any options the entity type has we have, so we can set choice
label here, and you can either set this to a call back or just the property on each
entity that you want to use. So let's just use ID. So we'll just use the ID now.
Beautiful. The ID is not super clear, but you can see that that's working. So let's
start removing them. I'm gonna remove 95, I'll hit save and continue editing. And it
went absolutely nowhere. If you're familiar with dealing with collections and the
Symfony form component, you might remember the fix. I'm gonna go over and set one
other form type option called by reference False. Now I'm not gonna go to the details
too much, but by setting, by reference to false, if an answer is removed from this
question that will force the system to call a re a remove answer method that I have
on my question, and that properly removes it from my entity and even sets the answers
question to no, if you wanna learn more about that, you can search for by reference

This time. If fire remove 95 again, and hit save, we get a different error. An
exception occurred, uh, not Noll violation on column question, ID of relation answer.
So what happened here and actually let me load my question entity back up is when we
remove an answer from question,

What we do is we actually set the question on the answer to no, it's what we do is
just sort of make the answer a what's called an orphan. That answer will, after this
change is no longer related to a question. However, inside the answer entity, we have
some code that says that this should never happen. Noble, false. We should never have
an answer that has no question. In reality. We need to kind of decide what we wanna
do here. If I remove an answer from a question, what should happen, probably what
should happen is that answer should just be deleted in a doctrine. There's a way to
force this. There's a way to say that, Hey, if an answer becomes orphaned, I want you
to delete it. It's called orphan removal. So inside of questions, scroll up to find
the answers property. Here it is. And on the end of this, I'm gonna add orphan
removal

Set to true. Now in refresh, yes, it worked. 95 is gone. And if you look in the
database, there is no answer with ID 95. Awesome. So the last problem with this
answers area is the same that we have with the other ones. If we have many answers in
the database, they're actually all gonna be loaded onto the page to render the select
element that's behind this. That's obviously not going to work. So we're just gonna
solve it in the same way by saying, by adding->auto complete. But when we refresh,
we're gonna get this error that says that the auto complete type that does not have a
choice label option. So behind the scenes, when we call auto complete, this actually
changes the form type behind the association field. And that form type doesn't have a
choice label option. It actually always relies on the two string method of your
entity, no matter what. So I'm gonna remove that and you can probably guess what's
gonna happen now because I removed that. Now it's saying, okay, I need to have a two
string method on answer. So let's finally add one an answer. I'll just go to the
bottom public function, underscored score, two string

Returns, a string, and I'm just gonna return this arrow, get ID and now beautiful. It
looks the same behind the scenes. If I search for stuff, search isn't super great,
um, Search. Isn't super great. Cause it's just my numbers, but you get the idea I'll
save and nice. All right, next, let's talk. Just, we're gonna talk just a tiny bit
more about fields, but what we're really gonna focus on is a power powerful system
called field configurators, where you can modify something about every field in the
system from one place. It's also a key to understanding how the core of easy admin
works.


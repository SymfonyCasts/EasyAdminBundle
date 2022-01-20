# Field Configurator

Coming soon...

Let's finish, configuring the rest of our fields and then talk about a super
important system that's running behind the scenes field configurators. So we're still
on question crowd controller. And one other field that I want to render heal is our
slug field. So now I'm saying yield field on call a new slug, and then I'm gonna say
hide on index. This is just gonna be there for the form system. So if I go to
question, it's not there. And if I edit a question, it is there now, as you probably
are familiar with slugs are typically auto generated from the name, but sometimes you
might, when you're creating a question, you might want to customize the slug somehow,
but once the slug has been created, it should never change. So on the create page, I
wanna allow the field to be here like it is, but on the edit page, I want to disable
this field. Now I could remove the field entirely pretty easily. I could add
something like, um, only one creating, and I would only show the field one creating,
but I would actually do wanna show the field, but just disable it when I am editing.

So to do that, as we know, every field has a form type behind it and every form type
in Symfony has an option called disabled. So I'm gonna say set form type option,
disabled, Disabled, but I don't wanna just set this true everywhere, cause that would
disable it everywhere. Just on the edit page. This is where the page name argument
that we're passed is really handy. This will be something like index or edit or
details. So I can say set disabled. The true if page name does not equal and I'll use
the little crud class here and say page new so we can use that constant. Let's try it
over here. I'm at a page disabled. If I go back to questions and create a new
question, we have a slug. All right, enough with the question section, let's go over
and configure the fields for our answers. So I'll CLO close question. Crowd
controller, open answers, crowd controller on comment, configure fields, because we
usually wanna control this And then I'm going to paste in some fields here. Oh, I
need to just retype the end of these classes and hit tabs so I can get my auto
complete for them. Perfect. So nothing special here. These are pretty basic at this
point. I might wanna add auto complete on my question and my answer by I'll leave
that to you,

But if we refresh the answer page looks awesome. And if I edit one, we're gonna get
our favorite air object of class question could not be converted to string coming
from our association field. So we know the solution is to go into question and add
public function, unders corner square, two string Returns, a string, and we're gonna
return this->name. Now that page works Now back on the answers page,

<affirmative>

Sometimes this text here might be a little too long to be showing on this index page.
So what I would like to do is truncate this. If it's past a, some length now,
technically fixing this right here is really easy. We can go over here onto our
answer field. And one of the methods I can call here set max. If I change this to a
text field, then one of the things I can call in here is set max length. So I can
literally say set max length 50, and that's going the truncate for me. But I am going
to undo that because what I wanna do is see if I can solve this globally for my
entire system. So these long fields are usually, So we're using field right here,
which we know has it kind of guess its field type it. You look at this in reality,
it's printing this as a text area. The field type that this really is, is a text area
field. And if I wanted to, I could type text area field right here. What I wanna do
is set a max length for every text area field, across our entire system. We can do
that with a field can configurator. I talked a little bit about these earlier.

What is going on here?

If you scroll down, I already have vendor, easy Corp, easy admin bundle, uh, opened
up. And one of the directories in there is called field and a sub directories is
called configurator. So after your field is created, it's passed through this
configurator system in any configurator. If they want to can make any changes to that
field. There are two really common ones. There's a common pre configurator, which is
called when your field is created. And this does a number of different, uh, things to
your field, including building the option, building, whether it's required sort of
role it's template path and other things. There's also a common post configurator,
which is run after your field is created, which does some different things on it. But
for the most part, these configurators are specific to the actual fields. So if
you're using a field and something kind of math is happening behind the scenes,
there's a good chance that that's coming from your configurator feel from a
configurator. So for example, the association configurator is a bit of a complicated
one, but it sets up all kinds of stuff behind the scenes, um, to kind of finish that
feel.

So this is important because this is a great way to look and understand what's going
on behind the scenes. Why some field is behaving in some way or how you can extend
it. But it's also great, cuz you can create your own custom field configurators like
we are going to do so up in our source directory. Here we go. I'm gonna create a new
directory called easy admin and inside of there a new piece glass called how about
truncate long text configurator, the only rule of these classes that they need to
implement a field configurator interface, I'm gonna go to code generate or command
and then a Mac go to "Implement Methods" to implement the two that you want. So
here's how this work for every single field that's added in the system. Easy. Admin's
gonna call our supports method and basically ask, does our configurator want to
operate on this specific field? The way these typically look is you say return true.
If field->get field fully qualified, class name = a specific field type. In our case,
we're gonna target text area, field types, test text, area, field, colon class.

So if the field that's being created is a text area field, then yes, we want to
modify it. So we return true from supports. Then configure is called an inside
configure just for now. Let's DD this field variable right here. All right, so I'm
gonna go over and it doesn't matter where I go. I'll just go to the index page and
boom, it gets hit. This field DTO is full of information like the value, the
formatted value, the form type, the template path. Pretty much everything. Now, one
thing notices that this is a field DTO class and when we're in our card, controllers
are dealing with field classes. So that's interesting. This is a pattern that easy
admin follows a lot inside of when we're just configuring things. We'll have an easy
class like field and field will give us lots of really nice methods on it to do
different things, to configure that field.

But you behind the scenes, the entire purpose of this field object or any of these
field classes is to take all the information that we give it and to create a field
DTO I'll actually open hold commander control, open up field, and you can actually
see this actually, lemme get a better, actually get a better example. I'm gonna call
format value temporarily, hold our command to jump into that. This actually jump us
into a field trait that that field uses. Check this out. One. We call format value.
What that really does is say this a DTO, a set format value cullable. So that DTO is
the field DTO. So we call nice methods on our field object, but we haven't seen, it's
just using all that information to craft this field DTO.

So this means that this field DTO contains basically the same information as our
field objects here, but the data is gonna look different. The methods you call on
them are gonna be a are, are gonna be a bit different. All right, so let's do our
truncating here. I'm gonna create a private concept here. Private cons max length =
how about 25 and down here, we'll say if T R Lynn of field->get formatted value,
formatted value, the string that it's about to, to print out, then just return don't
make any changes to the field. Just allow the formatted value to be returned like
normal. Now we're down here. We do wanna truncate it. So we can say that we can do
that with truncated value = and I'm gonna use a U function here and hit tab to a
complete that that just like class has actually added a use statement up here. This
gives me a nice string Unicode string object from the form components. What I can
pass here is field arrow, get formatted value.

And then on this, I can call truncate and I'll pass this. The max length, first
arguments length, the little.dot dot, and also a false for the last argument that,
that just that false led. The last argument just makes this, um, uh, cut it in the
little of a better way. Oh, and I forgot one of my colons there that now we're gonna
say field arrow, set, formatted value and pass it truncated value. So we're
overriding what the formatted value would be. So if we try this and refresh,
absolutely nothing happens. All of these still have the same length as before. So
what's happening. We know our configure methods are getting called. So do we have a
bug in our code?

Well, when we create a class and make an implement field configurator interface,
thanks to Symfony's auto configure feature. What this does is it, it gives a spec tag
to our service, a tag called EA field_configurator. And it turns out that's the key
to getting your field into the configurator system. You have to have a service with
that tag at your terminal run Symfony console Debu container. And we can actually
list all the services with that tag by saying dash tag = EA field_configurator.
Beautiful. And you can see there are a bunch of them inside of here And even a couple
like pre configurator and post configurator Have a priority on them. So that kind of
controls the order that they're in. Well, it turns out if you kind of looked into
here a little bit, you can see our Tru eight long text configurator, right there it
is. It is a priority of zero, like most of these. Um, and so kind of by chance, our
truncate long text configurator is being run before a different configurator that is
overriding the set format of value. If you dug, I believe it is actually this text
configurator down here. Let's actually see if that's the case, text configurator,
PHP,

And I'll hit look at all places open up text configurator. Yep. So text figure
grader, you see that operates on text fields and text area fields. And one of the
things it does is it sets the format of value. So what's happening is our class is
being called first we're setting the format of value. And then a second layer. This
text configurator is overriding our format of value. This is why it's kind of useful
to be able to find these classes looking side of them. So we want our configurer to
be called after this, do that. We need to give our configurator a negative priority
so that it drops below to the end of this and, and is called at the end to do that,
go to config services dot Yael. And we need to do a air time where we actually
configure a service manually. So app easy admin /truncate long text configurator. We
don't need to worry about the arguments or anything. Those will all, if we have any
construct arguments, those will still be auto wired. But we do to add a tag with name
EA dot field configurator, and then priority negative one is plenty pH.

So try out refresh and It still doesn't work. Let me run my debug container again.
Yep. You can see our negative one right there. Let me look over on our configurator.

Oh, that dummy. I actually need to put self max length there. So to fully test this,
let me comment out my configurator service here. So you can see that it doesn't work
originally thanks to the priority. And if I put this back set our prior negative one,
beautiful. It does truncate so messed up there in a little, a little bit in the
middle, but I recovered now, if you look at the detail page for this, you can see
that it also truncates it there. So could we truncate on the index page, but not on
the details page? Totally. It's just a matter of in our configurator, figuring out
which we're on. And one of the things that we're past here is this admin context.
We're gonna talk more about this later, but this is the object that holds all the
information about your admin section. So we can say crud = context, arrow, get crud.
This is literally gonna return the crud object that you we've been configuring inside
of our crud controllers and our dashboard controller. Now I'll say if, if crud

Arrow get current page = crud page detail, then just return and do nothing. So I
wanna refresh. Now we get the full thing right there. And one little geek detail
here. It's not really important, but there are some edge cases. We probably won't hit
it where context or get crud will actually return null. So I'm just gonna code
defensively by doing this. So if I hold commander control to open, get crud, you can
see returns a nullable crud, DTO, meaning it might not. There might not be a crud. So
by adding this here, it basically ignores that check. Next, let's create a custom
feel.


# Dashboard Page

Coming soon...

We know that on a technical level, the dashboard is the key to everything. All crud
controllers run in the context of this da of the dashboard, that link to them, which
us allows us to control things on a global level, by adding methods to this
controller. But more simply the dashboard is also just a page, a page with a
controller, with a controller, it's the homepage of our admin. And so we can and
should do something with this page. One simple option is just to redirect to one of
your other crowd pages. So whenever you go to /admin, you'd hit this, but you'd
immediately get redirected to, for example, the question admin in a little while,
we'll learn how to generate URLs to these specific credit controllers or a little
more fun. You can actually render something real on this page. Let's do that. We're
gonna render some stats and a cool chart.

So in order to run the stats that we need, we're gonna need to query the database
specifically. We're gonna need to query from the question repository. So our
controller is a normal controller, so it is a service. So I'm gonna add a
constructive and on wire. My question, repository question, repository service, I'll
head alt to enter and go to initialized properties to create that property and set
it. Now, if you're wondering why I'm not using action injection, where I just add the
argument here, I'm going to explain that in a few minutes. It is possible.

Now down here, I'm gonna render a template in a second, but first I'm gonna get a few
variable, uh, ready. So latest questions = this->question. Repository, arrow, find
latest. That's a custom method on my question, repository that I've already created.
And then top voted = this->question. Repository, arrow, find top code, another custom
method. Then at the bottom, like every controller you've created, we're just going to
return this->render and render a template. How about admin /index dot HTL twig? And
I'll pass this my two variables, latest questions and top voted. Awesome. All right,
let's go create that template templates, admin index dot HTL, twig, and I'll paste in
a structure. I'm actually just gonna paste in this template here. This is a super,
uh, normal template. One thing notice I'm extending easy admin page content that HTL
TWI. This is a, this is a to temple that you want to extend. In most cases, if you're
creating a normal action, but you want it to look like

It's inside of easy admin. This, uh, if we open this and actually went, if you open
this, see is not really much here. And it extends another temp called EA dote path
layout. That's a fancy way of it extending inside the views directory, this layout H
wick. So if you do a little bit of digging, you can find all of the different, uh,
blocks that in the mirror. She thought she looked cool. Like actually cool. She could
feel herself starting to well up. Nina came up behind her, put her arms around her
and said, you look like a million bucks, babe. This outfit made her feel like
herself.

We see our stuff. Awesome. Okay. So let's have a little more fun. Let's render a
chart for this. We're gonna use Symfony UX library. So we're gonna say

```terminal
composer require symfony/ux-chartjs
```

And while that's installing, I'm gonna over and go to
GitHub and load up that library and check out its documentation. So as you see, we
install this library, then once it finishes, we're gonna run yarn install dash force,
perfect. The library finish I'll run that. And just like that, we have a new stimulus
controller that has the ability to render a chart via chart JS.

So since we're not, I don't wanna talk too much about the chart library. We are
basically just going to steal the example code from inside of here. So notice we need
a service in order to build a chart called chart builder interface. So I'm gonna add
that as a second argument to my controller chart, builder interface, chart builder,
and then hit alt enter and go to initialized properties to create that property and
set it then at the bottom, just to keep things clean, I'm gonna create a new private
function called create chart. This is gonna return a chart object, and you'll see why
in a second. I'm I'm gonna copy all of this code here, all this example code And go
into my great chart paste that, And then at the bottom return chart. So that's that
chart object. And the only change I need to make is just say this->chart builder, to
reference that service, I'm not gonna bother making any of this, uh, stuff dynamic.
We just wanna see that the chart is working.

I

Now back up in our index method, perfect passing a new chart, variable to the
template called this arrow, create chart. Love it finally, to render this over in
index, that HT twig, I'm gonna add one more, a div down here with classicals call 12.
And from that Symfony, that UX chart JS library, we just installed. We get a render
chart function. So say render chart. We pass the chart variable that we just created
in the controller. And that should be it. So I'll head over refresh and nothing make
force refresh Nothing again.

And I get a huge air. Oh. And actually it says, try running yarn install dash force,
which is what I already did. But lemme try that again. Let me actually, oh yeah. I
had a build air. Let me actually restart yarn. So it now sees that that file is
there. Perfect. That compiled successfully now Tata, we have our chart. Cool. But I
wanna talk real quick about why I was avoiding action injection. So for both the
question or repository and chart builder interface, normally when I'm in a
controller, I'll take the lazy way out and I'll just add them here. So let's actually
try that at least for the chart builder interface. So I'm going to remove the chart
builder interface argument here, and instead put chart builder interface, chart
builder down there. And now I actually need to pass the chart builder into create
chart.

<affirmative> because down here I can't reference in as a property anymore. So I'll
say chart builder, interface, chart, builder, and we'll reference that argument.
Cool. So in theory, this should work just fine. We know action injection works. Um,
but you might already be seeing that peach storm is pretty mad here. And in fact,
when we refresh, we get the error dashboard controller index must be compatible with
abstract dashboard controller index. So the problem is that our parent class abstract
dashboard controller has an index method with no arguments. So it's not legal for us
to override that and add a, a required argument. It just doesn't work that way.

But if you do want action injection to work, there is a work around. And that is to
allow this argument to be null. So say = null that makes PHP happy in practice.
Symfony will pass the chart builder service. So this will work, but to code
defensively, I'm actually gonna put a little assert function. This may or may not be
something you're familiar with. It's just a function from PHP. And instead of here,
we can put a little expression. Noel does not equal chart builder. So if this is
basically asserting that chart builder does not = no if chart builder, for some
reason does equal, no, this will throw an exception. So now we can kind of
confidently know down here that we do have a chart builder interface object. So now
in refresh, got it. So a little bit easier than, uh, doing construction injection
though. It is a little ugly for one other advantage of using action injection. Like
this is that the chart builder object won't actually be instantiated unless the index
method is called. So if we're for example over, so it makes it a little lazier. Next
let's learn how to override templates, easy admins, layout template, or how the ID
field is rendered across our entire admin area.


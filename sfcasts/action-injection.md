# Service Action Injection

Coming soon...

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

# Field Template

Coming soon...

We know that these fields describe both the form type that you see on the form and
also how that field is rendered on the detail and on the index page. And we already
know that we can customize the form behind this really easily. We can set, set form
type to use a completely different form type, or we can set form type option to
configure one of the options. So we already know how to take a lot of control over
how the field is rendered in the form. You can also change a lot about how it renders
on the index X and details page. Like for example, let's play with this votes thing
here, field here by auto complete the methods on this. We have things like set CSS
class a to Webpack Encore entries, add HML contents to body, to head. There are many
ways to customize, uh, how this field renders. You can even call set template path to
completely over ride how this field is rendered on the index in details page, which
is something that we're gonna do. But also notice that there's set template path and
set template name like what's going on. What's the difference between those two?

Well, to answer that question, I'm gonna hit shift shift and open up a core class
from easy Adam called template registry. If you don't see, it makes you include all
non-project items. Perfect. So internally easy admin has a bunch of templates and it
maintains this map of sort of template names and then the actual templates behind
them. So when you call set template name, what you would pass here is some other
template name. So for example, I could pass crud field money here. If I wanted to use
that template instead of the normal one, but you're not really gonna be setting the
template name very often. Most of the time, if you wanna completely override at how a
field is rendered, you are gonna, uh, call set template path. So let's do that. Let's
just take control of this. When votes is rendered on the index and template page, I
want to com render a completely different template.

Let's have a call. How about admin /field /votes.ht L that twig, and then let's go
create that. So templates I'll create a new directory admin sub directory field. Here
we go. And a new file called votes dot H HG, all that twig inside of here. I don't
really know what to put yet. So I'm just gonna put a hundred votes and see what
happens. So when I move over and refresh, there we go. We are now incomplete control
of the votes. So of course the obvious question here is what variables do we have
access to? Like what kind of stuff can we do in here? One of the really important
things to realize, and you can kind of see it here in the template registry is that
every single field has a corresponding template. And if you need to extend or change
how a field is rendered, looking in the parent template is pretty handy, for example,
and this is an integer field.

Well, there's a template called integer dot H twig. So I'm gonna close this template
registry and let's actually go find that. So on vendor composer, easy admin source,
I'll close up field and instead open resources, views, crud, and there is the list of
all of the field templates in the system. You can also see other templates here that
are used to render other parts of the system, and you can override these as well.
Let's open integer dot H channel that twig. So you can see it's super, uh, you can do
two things here. The first thing is three lines of com. This is really cool. This is
a way to help hint to our editor and us, what variables we have access to.
Apparently, apparently we have access to the field variable, which is that very
familiar field DTO object. We were just talking about. And all the integer does is
just say field dot, formatted value. So I'm gonna copy these three lines into ours so
that we can get a little bit of auto completion help. And then now Sarah, instead of
hundred votes, we know we can say field that formatted value. And then I'll just say
votes on the other side, why we try this beautiful. Let's make this a little fancier.

If these votes are negative, let's put a little thumbs down. If they're positive,
let's put a little thumbs up. So I'll take off the word votes here. And before this,
we can do an if statement, if field dot and what I wanna get is the actual underlying
value. We can get that by saying field dot value. So formatted value is the string
format that would be used to print on the page value, actual true underlying in this
case, integer. So a field that value is greater than equal to zero else. And if, and
if it is greater than zero, I'll add a little icon here and I'll copy that for our
little thumbs down with text danger and just like that we have customized how this
field renders. It doesn't customize how it shows inside of our form. That's entirely
handled by the form field, but it does control how it's rendered on the index page.
And I'll how it's rendered on the details page. But we also have a votes field inside
of questions. And while it would be pretty easy, just to point this votes field at
the same template, instead of doing that, I wanna create a totally new custom field
and easy admin that's next.


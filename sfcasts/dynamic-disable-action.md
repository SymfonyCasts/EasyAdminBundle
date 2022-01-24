# Dynamic Disable Action

Coming soon...

We've done a good job of hiding the delete action, conditionally and disallowing
deletes under that same condition, but it would be much simpler if we could truly
disable the delete action on entity by entity basis, then easy admin would naturally
just hide the delete link to figure out how to do this. Let's click into our base
class abstract crud controller and go down here to where any of our actions are. So
check out in every action like index or detail or delete or past something called an
admin context. This is a configuration object that holds everything about your admin
section, including information about what actions should be enabled. So by the time
our action method has actually been called our actions. Config has already been used
to create the action objects. So by the time we get here, we figured out what actions
we want and look what it does here. It dispatches an event. So I'm wondering if we
can hook into this event and actually change that action fit conditionally before the
rest of the, uh, action runs and before the template renders.

All right. So I'm actually going to scroll up to before credit controller. I'm
actually search for that. There it is for credit action event. I'm gonna copy that so
that we can spin over here. Run Symfony console, make subscriber, let's call it hide
action subscriber. And I'm gonna paste my long event name right there. Beautiful.
Let's go see what that subscriber looks like should be pretty familiar. We've got the
setup. We've got the method. And to start here, let's just DD event. Wanna refresh
that gets hit immediately because that event is dispatched before every single crud
action. The hardest part of figuring out how to dynamically disable us event is just
figuring out where all the data is. So you can see, we have the admin context inside
the admin context amongst among other things. There's something called a crud D ETO
inside the crud DTO. We have an action config, DTO. This holds information about all
the actions, including the index, the current page name and all of our actions.
Config the action config shows us for each page, which array of action objects should
be enabled for that. So for the edit page, we have these two action DTO objects and
each action DTO object contains all the information about what that action should
look like few. Yeah.


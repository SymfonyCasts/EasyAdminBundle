# Controlling the "Formatted Value"

Head back to the index page. One of the nice things about the `ImageField` is that
you can click to see a bigger version of it. But let's pretend that we *don't* want
that for some reason... like because these are meant to be tiny avatars.

Actually, EasyAdmin has a field that's made specifically *for* avatars. It's called
`AvatarField`!

Back in our code, `yield AvatarField::new()` and pass it `avatar`:

[[[ code('caef1a87d9') ]]]

Yes, we *do* temporarily have two fields for `avatar`. Go refresh and...
the original works, but the `AvatarField` is broken!

Inspect the image. Yup! This looks like the same problem as before: it's dumping
out the filename instead of the full path to it. To fix this, the `ImageField`
has a `->setBasePath()` method. Does that method exist on `AvatarField`? Apparently
not!

## Controlling the "Formatted Value"

So let's back up. No matter which field type you use, when a field is ultimately
printed onto the page, what's printed is something called the _formatted value_.
For some fields - like text fields - that _formatted value_ is just rendered by
itself. But for other fields, it's wrapped inside some markup. For example, if you
dug into the template for the `AvatarField` - something we'll learn to do soon -
you'd find that the formatted value is rendered as the `src` attribute of an
`img` tag.

Anyways, the formatted value is something we can control. Do that by calling
`->formatValue()` and passing a callback. I'll use a `static function()` that
will receive a `$value` argument - whatever EasyAdmin would *normally* render
as the formatted `$value` - and then our entity: `User $user`. Inside, we can
return whatever value should be printed inside the `src` of the `img`. So,
`return $user->getAvatarUrl()`:

[[[ code('ce902c4b77') ]]]

The `static` isn't important... it's just kind of a "cool kid" thing to do if
your callback does *not* need to leverage the `$this` variable.

Anyways, go back to your browser and refresh. Yay! We have a nice little avatar!
But, if you go the the form for this user, interesting! It only renders *one*
of our avatar fields. This is expected: even though we can *display* two avatar fields
on the index page, we can't have two avatar fields in the *form*. The second one
always wins. And that's fine. We don't actually *want* two fields... it's just nice
to understand why that's happening.

If we *deleted* the `ImageField` and used the `AvatarField` on the form, you'd
see that the `AvatarField` renders as a text input! Not very helpful. Ultimately,
we want to use `ImageField` on the form and `AvatarField` when rendering. And we
already know how to do that!

Down here... on `ImageField,` add `->onlyOnForms()`. And above, on `AvatarField`,
do the opposite: `->hideOnForm()`:

[[[ code('0ab85b72c2') ]]]

This gives us the *exact* result we want.

## Allowing Null in formatValue

Oh, and I almost forgot! In the `->formatValue()` callback, technically the `User`
argument should be allowed to be null. We'll learn *why* later when we talk about
entity permissions. In a real project, I would make the function look like this:

```php
->formatValue(static function($value, ?User $user) {
    return $user?->getAvatarUrl();
})
```

That has a nullable `User` argument and uses a PHP 8 syntax that basically says:

> If we have a `User`, then call `getAvatarUrl()` and return that string. But if
> we *don't* have a user, skip calling the method and just return `null`.

I'm actually going to remove this for now... because we'll re-add it later when we
hit an error.

Next, I want to customize *more* fields inside of our admin! In particular,
I'm excited to check out the very powerful `AssociationField`.

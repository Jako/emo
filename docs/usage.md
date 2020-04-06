## How it works

This plugin searches for `mailto:` strings in href attributes and all plain text
email addresses in the html output. However, only email addresses like
`user@host.com` are matched whereas `Tog@ther` is left untouched.

Matches of all plaintext emails and email links are consequently replaced with
span elements only containing a note to turn on javascript. Then email addresses
and original link text are encrypted and stored in javascript variables.
Decryption routines triggered by the browser on `window.onload` event are
located in the script file `emo.js`. Optionally, CSS class `emo_address` can be
used to configure the appearance of email links.

It is still believed that hexadecimal or unicode encoding will stop spam-bots
being able to find your email address. Nevertheless, encoded email address
harvester are on the way. Unlike other obfuscation plugins, this one uses real
encryption instead of using an out-dated and over-used hack such as hexadecimal
or unicode encoding (not encryption) of email addresses. Also, all traces of
href attributes as well as `mailto:` strings and `@` characters are hidden from
spam-bots.

The plugin does not modify adresses inside `<form>` tags. So posted and not
validated forms do not break. Sections between  `<!-- emo-exclude -->` and
`<!-- /emo-exclude -->` are excluded from replacement.

If the `No javascript` message contains a number, a link to a MODX resource with
that ID is generated. This could i.e. point to a resouce with a contact form.

## System Settings

emo uses the following system settings in the namespace `emo`:
   
Property          | Description                                                   | Default
----------------- | ------------------------------------------------------------- | -------
tpl_only          | Don't work on resources with blank template                   | Yes
selection_type    | Selection type for enabled/disabled resources for emo         | -
selection_range   | Comma separated list of enabled/disabled resource IDs for emo |
js_path           | Path to emo javascript                                        | `/assets/components/emo/js/emo.min.js`
css_path          | Path to emo css                                               |
no_script_message | 'No javascript' message (Resource ID for internal link)       | Turn on JavaScript!

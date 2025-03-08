## How it works

This plugin searches for `mailto:` strings in href attributes and all plain text
email addresses in html output. However, only email addresses like
`user@host.com` are matched, while `Tog@ther` is left untouched.

Matches of all plain text emails and email links will be replaced with span
elements containing only a note to enable javascript. Then email addresses and
original link text are encrypted and stored in javascript variables. Decryption
routines triggered by the browser on the `window.onload` event are located in
the `emo.js` script file. Optionally, the CSS class `emo_address` can be used to
configure the appearance of email links.

It is still believed that hexadecimal or unicode encoding prevents spambots from
finding your email address. Nevertheless, encoded email address harvesters are
on their way. Unlike other obfuscation plugins, this one uses real encryption
instead of an outdated and overused hack like hexadecimal or unicode encoding
(not encryption) of email addresses. It also hides all traces of href attributes
as well as `mailto:` strings and `@` characters from spambots.

The plugin does not change addresses inside `<form>' tags. So submitted and not
validated forms will not break. Sections between `<!-- emo-exclude -->` and
`<!-- /emo-exclude -->` are excluded from substitution.

If the `No javascript` message contains a number, a link to a MODX resource with
that ID is generated. For example, this can point to a resource with a contact
form.

## System Settings

emo uses the following system settings in the namespace `emo`:

| Key                   | Name                                                          | Description                                                                                                                                                                                           | Default        |
|-----------------------|---------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------|
| emo.adresses_tpl      | Adresses Chunk                                                | Name of a chunk that contains the Javascript for the encrypted addresses at the end of the body.                                                                                                      | tplEmoAdresses |
| emo.css_path          | Path to emo css                                               | If this setting is set, a link to this css path is inserted at the end of the head area of the html code of the current resource.                                                                     | -              |
| emo.debug             | Debug                                                         | Display debug information.                                                                                                                                                                            | No             |
| emo.include_scripts   | Include emo javascript                                        | If this setting is set, a link to this js path is inserted at the end of the body section.                                                                                                            | Yes            |
| emo.js_path           | Path to emo javascript                                        | With this setting a different location of the emo javascript than {assets_url}components/emo/js/emo.min.js can be provided.                                                                         | -              |
| emo.no_script_message | ‘No javascript’ message (Resource ID for internal link)       | All email addresses in the current document were replaced with the text in this setting. If the text is numeric, a link to the resource with that ID and the pagetitle of that resource is generated. | -              |
| emo.selection_range   | Comma separated list of enabled/disabled resource IDs for emo | Insert a comma list with resource IDs, the plugin should (not) work on.                                                                                                                               | -              |
| emo.selection_type    | Selection type for enabled/disabled resources for emo         | If only some resources should worked by the plugin, change the value of this setting to `include`.                                                                                                    | exclude        |
| emo.tpl_only          | Don’t work on resources with blank template                   | Enable this, if the plugin should work on resources with the `(blank)` template.                                                                                                                      | Yes            |

## Output issues

Please make sure that the HTML output of MODX uses only UTF-8 characters.
Otherwise, the output may break and display a blank page. This can happen if a
non-UTF-8 locale is set in the MODX system preferences and a date modifier is
used. Also, the UTF-8 characters must be [precomposed](https://en.wikipedia.org/wiki/Precomposed_character).

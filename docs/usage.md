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
that ID is generated. For example, this could point to a resource with a contact
form.

## System Settings

emo uses the following system settings in the namespace `emo`:
   
| Property          | Description                                                                                     | Default                                |
|-------------------|-------------------------------------------------------------------------------------------------|----------------------------------------|
| adresses_tpl      | Name of a chunk that contains the Javascript for the encrypted adresses at the end of the body. | tplEmoAdresses                         |
| css_path          | Path to emo css                                                                                 |                                        |
| js_path           | Path to emo javascript                                                                          | `/assets/components/emo/js/emo.min.js` |
| no_script_message | 'No javascript' message (Resource ID for internal link)                                         | Turn on JavaScript!                    |
| selection_range   | Comma separated list of enabled/disabled resource IDs for emo                                   |                                        |
| selection_type    | Selection type for enabled/disabled resources for emo                                           | -                                      |
| tpl_only          | Don't work on resources with blank template                                                     | Yes                                    |

## Output issues

Please make sure that the HTML output of MODX uses only UTF-8 characters.
Otherwise, the output may break and display a blank page. This can happen if a
non-UTF-8 locale is set in the MODX system preferences and a date modifier is
used. Also, the UTF-8 characters must be [precomposed]
(https://en.wikipedia.org/wiki/Precomposed_character).

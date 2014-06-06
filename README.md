TYPO3.email2powermail
=====================

converts mailto into form links to a powermailform

Inclusion in Powermailform
==========================

Example how to render an image to the powermailform with some chars left out

```
#Build receiver image
plugin.tx_powermail_pi1.lib{
  emailgraphic < plugin.tx_email2powermail.getEmail
  emailgraphic.10.conf.tx_email2powermail_emailcache >
  emailgraphic.10.conf.tx_email2powermail_emailcache = IMAGE
  emailgraphic.10.conf.tx_email2powermail_emailcache {
    file = GIFBUILDER
    file {
      #backColor = #FFFFFF
      transparentBackground=1
      10 = TEXT
      10 {
        text.field = email
        text.substring = 0,-10
        fontColor = #000000
        fontSize = 14
        fontFile = fileadmin/templates/fonts/arial.ttf
        offset = 0,5+[10.h]
        niceText = 0
      }
      20 = TEXT
      20 {
        text = ...
        offset = [10.w]+5,5+[10.h]
        fontFile = fileadmin/templates/fonts/arial.ttf
        niceText = 0
      }
      30 < .10
      30 {
        offset = [10.w]+[20.w]+10,5+[10.h]
        text.substring = -7,0
      }
      XY = 35+[10.w]+[20.w]+[30.w],10+[10.h]
    }    
  } 
  emailgraphic.wrap = <label>Empfänger</label>|
}
```

+++++++++++ Implemented third party tools +++++++++++
- chart.js
- chart.js-plugin-datalabels
- jQuery.js
- jquery.waypoints.min.js

+++++++++++ How to use +++++++++++
The "genius budget book" can be used by hosting a local server via XAMPP.
To use it via XAMPP:
1) download XAMPP (https://www.apachefriends.org/de/download.html)
2) install XAMPP with all extensions into
3) move "Genius-Budget-Book" folder into ...\xampp\htdocs
4) start xampp-control.exe at ...\xampp\xampp.control.exe
5) first start Apache, the start MySQL
6) open browser (Google Chrome if possible)
7) open following URL: http://localhost/GeniusBudgetBook/

+++++++++++ ADDITIONAL INFOS +++++++++++
Because of missing responsetivety it is recommended to view on the 1920x1080 px display format.

You can manually view, edit, save and delete the database at:
http://localhost/phpmyadmin/
username: root
password: 

+++++++++++ Bug-Fixing for MySQL +++++++++++
Problem: "Error: MySQL shutdown unexpectedly"
Fix: delete "aria_log_control" at ...\xampp\mysql\data

Problem: browser gets stuck loading
Fix:
    1) exit XAMPP and close browser
    2) go to ...\xampp\mysql
    3) rename "data" to "data-old"
    4) rename "backup" to "data"
    5) copy folder "geniusbudgetbook" from "data-old" to "data"
    6) copy "ibdata1" from "data-old" to "data"
    7) restart XAMPP and browser
use strict;
use CGI;

my $cgi = new CGI;

print $cgi->header('text/html');
print $cgi->start_html;
print $cgi->end_html;
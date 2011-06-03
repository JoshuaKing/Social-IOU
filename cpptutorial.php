<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/green.css">
	<link rel="stylesheet" href="css/prettify.css">
	<script src="js/jquery.js"></script>
	<script src="js/facebook.js"></script>
	<script src="js/prettify.js"></script>
	<?php require_once("minihtml/analytics.html"); ?>
</head>
<body onload="prettyPrint()">
	<?php require_once("minihtml/header.html"); ?>
	<div id="dashboard">
		<h1>C++ Tutorial</h1>
		<h2>So you wanna learn C++ ?</h2>
		<p>Hey there :) so you wanna learn C++ ey? Well this is my attempt at
		teaching you it!</p>
		<p>Okay, so lets get into it! A nice 'Hello World' program in C++</p>
		<pre class="prettyprint linenums">
#include &lt;iostream&gt;

int main() {
	std::cout &lt;&lt; "Hello, World!";
	return 0;
}
		</pre>
		<p>So what was all the stuff i just wrote? Well let me explain:<br/><br/>
		<mark>Line 1: </mark><code>#include</code> tells your compiler to "include" some information from a library (similar to "import" for python).  In this case, we have included the <code>iostream</code> library (input/output stream) - <code>cout</code> is included in this library<br/>
		<mark>Line 3: </mark>This defines a the "main" function - it is a special function - it is the entry point for your program! The <code>int</code> says that the function returns an integer<br/>
		<mark>Line 4: </mark>This is a bit trickier, <code>std::cout</code> is the std (standard) namespace (more on this later, it is complex), and the <code>cout</code> function (c output) used for displaying text in C++. The &lt;&lt; operator says to send the "Hello, World!" data to the display.<br/>
		<mark>Line 5: </mark><code>return 0</code> makes the program return from the function with value 0 (0 is a normal, successful exit)
		</p>
		<p>Some extra information, C++ ends its lines with semicolon (';') - this is imperative! If you come across strange compiler errors, you might want to check the line above the error, and see if it does have a ; at the end of it.
		Don't worry, all of us forget them every so often! If you haven't - then you haven't coded enough ;)</p>
		<p>You may be wondering about comments. In C++, comments are defined with a preceding <code>//</code> eg.</p>
<pre class="prettyprint linenums">
// This is a comment, it is totally ignored,
// and used to help others (and yourself) understand
// what you are doing in your code, it is 'mini-documentation'
// if you like.
</pre>
		<p>But that can get pretty annoying, and messy, if your writing multi-line comments like the one above, so this is an alternative!</p>
<pre class="prettyprint linenums">
/*
 * Weeee! This is a multi-line comment, useful
 * for documenting functions, or explaining key parts
 */
 
/* This is not multi-line, but can still be done! */

/*
This is a messy multi-line comment, but it is all that is required
Try to use the above two - it is convention, and it is easier to read
*/
</pre>
		<p>Remember that in programming, there is <mark>always</mark> more than one way to solve a problem. Always.
		Now that you understand this Hello World program, we shall move on.</p>
		<a href="./cpptutorial2.php">Next (Variables)</a>
	</div>
</body>
</html>
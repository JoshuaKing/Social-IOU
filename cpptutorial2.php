
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
		<h2>Variables</h2>
		<p>Welcome to part 2! Now that you are here, there is no turning back.</p>
		<p>Okay, variables, simply put, are like your algebra in math - they give names to values.  However, these might not only be numbers, they could also be characters (eg. 'a', 'A' and '2'), strings (like "Hello, World!") or any number of other types.
		They are defined like this: <code>type variablename;</code> or <code>type variablename = somevalue;</code> egsample:</p>
<pre class="prettyprint linenums">
// An integer variable:
int myinteger;
myinteger = 2;

// Another integer:
int anotherint = 3;

// And two more:
int i, j;
i = j = 4;

// And some more:
int a = 5, b = 6, c = 7;
</pre>	
		<p>Alright, depending on what programming experience you have, you may be wondering why we need to define a function to return a specific value (an integer, in the case of <code>main</code>).
		Well, everything has a type in C++, including functions. This is actually the case in all languages, but some (eg. python) take care of it for you behind the scenes.
		So what are these 'types'? Well they mean different things, here are a few common ones:<br/><br/>
		<code>int</code>: An integer (eg. -2, -1, 0, 1, 2 etc) cannot be 3.14 (if you say <code>int a = 3.14;</code> then 3.14 will be truncated (rounded down) to 3 (so a will equal 3)<br/>
		<code>char</code>: A character (eg. 'a', 'A', '1', ' ' etc). Note the single quote! "a" is not the same as 'a'! A character is just the same as an integer, but may only be 0 to 255 - each representing an ASCII character. For egsample, if you wrote <code>char mycharacter = 66;</code> then mycharacter would equal 'B' (which is ascii character 66) so
		you can do some cool things, like:</p>
<pre class="prettyprint linenums">
/* define some characters */
char mycharacter = 66;	// the 'B' character
char iloveb = 'B';		// also the 'B' character

/* now mycharacter is equal to mycharacter! */
mycharacter = 'B';
iloveb = 66;

/* still equal :) */
</pre>
		<p>
		If you want to see a list of the ascii codes, view <a href="http://asciitable.com/">www.asciitable.com</a>.<br/>
		<code>float</code>: Can store a "floating point" number (eg. 2.0, 3.14, 1000000.698234 etc)
		</p>
		<p>That should keep you going for a while, but to make more useful applications you must learn more!</p>
		<a href="./cpptutorial3.php">Next (Input and Output - Communicating with the user)</a>
	</div>
</body>
</html>
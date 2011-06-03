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
		<h2>Input and Output - Communicating with the user</h2>
		<p>Your still here ey? Well good, cause this is were C++ gets interesting. Hard to imagine, i know, but trust me!</p>
		<p>So you remember the Hello World example program? Well some of that is probably still pretty wierd, right? I mean, how does <code>cout</code> work?
		Well, <code>cout</code> (<code>std::cout</code>) works like this: It prints out a "stream" of characters or other data to the display.
		It is a very versatile tool, and you can add multiple different pieces of information together too!</p>
<pre class="prettyprint linenums">
#include &lt;iostream&gt;

int main() {
	float pie = 3.14159265;	// Yes, i memorized those digits, i have no life -.-
	std::cout &lt;&lt; "The meaning of life is " &lt;&lt; 42 &lt;&lt; " And i love " &lt;&lt; pie;
	return 0;
}
</pre>
		<p>Just so you know, the newline character
		(responsible for each new line) is represented by "escaping" an 'n' resulting in <code>'\n'</code>. <code>'\n'</code> is one character, the \ preceding the n lets the compiler know
		that you actually want a new line there. This is also useful for tabs: <code>'\t'</code> and if you want to write just the '\' character, then you must escape it too,
		thus <code>'\\'</code> is the \ character. If you like, <code>std::endl</code> is also the same thing as <code>'\n'</code>, so you can use that if you prefer.</p>
<pre class="prettyprint linenums">
#include &lt;iostream&gt;

int main() {
	std::cout &lt;&lt; "Here is a table of info:" &lt;&lt; std::endl;
	
	/* Here, please note how i do not end the line with a ;
	 * That means i can continue onto the next line! But eventually,
	 * it too will end with a ;
	 */
	std::cout &lt;&lt; 123 &lt;&lt; "\t\t" &lt;&lt; 456 &lt;&lt; "\t\t" &lt;&lt; "789\n"
		  &lt;&lt; "Hello\t\tWorld!\t\t" &lt;&lt; 2.718 &lt;&lt; std::endl;
	return 0;
}
</pre>
		<p>So <code>cout</code> can print out some data, so what? How much use is that if you can't ask the user to provide some information? <code>cin</code> to the rescue!</p>
		<p>What is <code>cin</code> (<code>std::cin</code>)? It works very similar to <code>cout</code> - and stands for c input. Rather than talk about it,
		you'll probably find it easier if i gave you a quick egsample:</p>
<pre class="prettyprint linenums">
#include &lt;iostream&gt;

int main() {
	int code;
	
	std::cout &lt;&lt; "Hello, and welcome to SKYNET.\n";
	std::cout &lt;&lt; "Please enter your security code: ";
	
	std::cin &gt;&gt; code;
	
	std::cout &lt;&lt; "Code " &lt;&lt; code &lt;&lt; " is UNAUTHORISED.\n";
	return 0;
}
</pre>
		<p>It may be hard to remember which way the &lt; and &gt; brackets go. Just think that with <code>cin</code>, you are pushing the data <i>into</i> the variable, and
		<code>cout</code> pushes data to the display. Well... thats how I remember it :/</p>
		<p>So you may be thinking, the previous egsample is stupid! I mean, it always says you are unauthorised! Well, you're not wrong, onto if statements!</p>
		<a href="./cpptutorial4.php">Next (Program Flow)</a>
	</div>
</body>
</html>
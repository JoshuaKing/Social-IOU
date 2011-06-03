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
		<h2>Program Flow</h2>
		<p>Hopefully you understand up to here, but I have been moving pretty fast, so don't
		feel bad if you don't quite get it yet!</p>
		<p>Alright, so you want to create Skynet. Well, lets start on our way. First, we need
		to make sure that the user is authorised to turn on Skynet. For this, we will need to
		check if the user has put in the correct code. We need what is called an "if statement"
		for that. Here is what one looks like:</p>
<pre class="prettyprint linenums">
#include &lt;iostream&gt;

int main() {
	int code;
	
	std::cout &lt;&lt; "Hello, and welcome to SKYNET.\n";
	std::cout &lt;&lt; "Please enter your security code: ";
	
	std::cin &gt;&gt; code;
	
	/* == compares the two values and returns true
	 * if the values are the same.
	 */
	if (code == 1337) {
		/* The if statement executes what is inside the {}
		 * if the conditions are met. Just like the main
		 * function executes what is inside the {} brackets
		 */
		std::cout  &lt;&lt; "Code " &lt;&lt; code &lt;&lt; " is AUTHORISED.\n";
		return 0;
	}
	
	std::cout &lt;&lt; "Code " &lt;&lt; code &lt;&lt; " is UNAUTHORISED.\n";
	
	// Let's return a different value this time
	return -1;
}
</pre>
		<p>Woh thats a lot of code! Perhaps your starting to right real programs now... Okay, so that is
		an if statement. You can use <code>else</code> to say <code>if (condition) { /* do stuff */ } else {
		/* do other stuff */}</code>. or <code>else if</code> too, for many things.</p>
		<p>There are many other things which you might find useful, such as a for loop and while statement,
		so lets fast-track this and put everything into one program! Alright, take a deep breath and don't
		panic, just mull over it for a bit, and when you understand it, continue.</p>
<pre class="prettyprint linenums">
#include &lt;iostream&gt;

int main() {
	int code;
	
	std::cout &lt;&lt; "Hello, and welcome to SKYNET.\n";
	std::cout &lt;&lt; "Please enter your security code: ";
	
	std::cin &gt;&gt; code;
	
	/* != compares the two values and returns true
	 * if the values are NOT the same.
	 */
	if (code != 1337) {
		std::cout  &lt;&lt; "Code " &lt;&lt; code &lt;&lt; " is UNAUTHORISED.\n";
		
		// Return a different status
		return -1;
	}
	
	std::cout &lt;&lt; "Code " &lt;&lt; code &lt;&lt; " is AUTHORISED.\n";
	std::cout &lt;&lt; "Welcome, human.\n\n";
	
	std::cout &lt;&lt; "[1] Fire Nuke's\n";
	std::cout &lt;&lt; "[2] Fire More Nuke's\n";
	std::cout &lt;&lt; "Type the number to choose.\n";
	
	// Get input from the human
	int nukes;
	std::cin &gt;&gt; nukes;
	
	// What did the user press?
	if (nukes == 1) {
		/* For loops are somewhat complex, take care.
		 *
		 * The first part (before the first semicolon) is the initialisation
		 * of the counter (the counter is called i, in  this example).
		 *
		 * The second part is the condition, when this is false,
		 * the for loop will stop.
		 *
		 * The last part is what is changed each time the for loop completes.
		 * You can increment up (with ++), down (with --) or somewhere
		 * inbetween (eg. i += 4 or i *= 3)
		 */
		for (int i = 0; i &lt; 10; i++) {
			std::cout &lt;&lt; "Launching Nuke " &lt;&lt; i+1 &lt;&lt; "!\n";
		}
	
	} else if (nukes == 2) {
		int fire = 20;
		
		// while loops execute until the condition becomes false
		while (fire > 0) {
			std::cout &lt;&lt; "Launching Nuke " &lt;&lt; fire &lt;&lt; "!\n";
			fire--;	//-- means 'decrement by one': very useful!
		}
	
	} else {
		std::cout &lt;&lt; "You silly human!\n";
		std::cout &lt;&lt; "YOU WILL PAY FOR YOU INSOLENCE!\n";
		return -2;
	}
	
	std::cout &lt;&lt; "Thank you, goodbye\n";
	return 0;
}
</pre>
		<p>Okay, read the comments, and if you can understand enough of it, lets move on to namespaces and functions.</p>
		<a href="./cpptutorial5.php">Next (Namespaces and Functions)</a>
	</div>
</body>
</html>
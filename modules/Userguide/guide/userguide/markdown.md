# Markdown Syntax

- [Headers](#headers)
- [Paragraphs](#paragraphs)
- [Links](#links)
- [Code blocks](#code-blocks)
- [Unordered Lists](#ul)
- [Ordered Lists](#ol)
- [Nested Lists](#nl)
- [Italics and Bold](#ib)
- [Horizontal Rules](#hr)
- [Images](#images)
- [Tables](#tables)

<a name="headers"></a>
### Headers

    # Header 1
	
	## Header 2
	
	### Header 3
	
	#### Header 4


<a name="paragraphs"></a>
### Paragraphs
~~~
Regular text will be transformed into paragraphs.
Single returns will not make a new paragraph, this
allows for wrapping (especially for in-code
comments).

A new paragraph will start if there is a blank line between
blocks of text.  Chars like > and & are escaped for you.

To make a line break,  
put two spaces at the  
end of a line.
~~~
Regular text will be transformed into paragraphs.
Single returns will not make a new paragraph, this
allows for wrapping (especially for in-code
comments).

A new paragraph will start if there is a blank line between
blocks of text.  Chars like > and & are escaped for you.

To make a line break,  
put two spaces at the  
end of a line.

<a name="links"></a>
### Links
~~~
This is a normal link: [Laravel](http://laravel.org).

This link has a title: [Laravel](http://laravel.org "The swift PHP framework")
~~~
This is a normal link: [Laravel](http://laravel.org)

This link has a title: [Laravel](http://laravel.org "The swift PHP framework")

<a name="code-blocks"></a>
### Code blocks

	For inline code simply surround some `text with tick marks.`
	
For inline code simply surround some `text with tick marks.`

	// For a block of code,
	// indent in four spaces,
	// or with a tab

You can also do a "fenced" code block:

	~~~
	A fenced code block has tildes
	          above and below it
	This is sometimes useful when code is near lists
	~~~
~~~
A fenced code block has tildes
		  above and below it
This is sometimes useful when code is near lists
~~~

<a name="ul"></a>
### Unordered Lists

~~~
*  To make a unordered list, put an asterisk, minus, or + at the beginning
-  of each line, surrounded by spaces.  You can mix * - and +, but it
+  makes no difference.
~~~
*  To make a unordered list, put an asterisk, minus, or + at the beginning
-  of each line, surrounded by spaces.  You can mix * - and +, but it
+  makes no difference.

<a name="ol"></a>
### Ordered Lists

~~~
1.  For ordered lists, put a number and a period
2.  On each line that you want numbered.
9.  It doesn't actually have to be the correct number order
5.  Just as long as each line has a number
~~~
1.  For ordered lists, put a number and a period
2.  On each line that you want numbered.
9.  It doesn't actually have to be the correct number order
5.  Just as long as each line has a number

<a name="nl"></a>
### Nested Lists

~~~
*  To nest lists you just add four spaces before the * or number
	1. Like this
		*  It's pretty basic, this line has eight spaces, so its nested twice
	1. And this line is back to the second level
		*  Out to third level again
*  And back to the first level
~~~
*  To nest lists you just add four spaces before the * or number
	1. Like this
		*  It's pretty basic, this line has eight spaces, so its nested twice
	1. And this line is back to the second level
		*  Out to third level again
*  And back to the first level

<a name="ib"></a>
### Italics and Bold

~~~
Surround text you want *italics* with *asterisks* or _underscores_.

**Double asterisks** or __double underscores__ makes text bold.

***Triple*** will do *both at the same **time***.
~~~
Surround text you want *italics* with *asterisks* or _underscores_.

**Double asterisks** or __double underscores__ makes text **bold**.

___Triple___ will do *both at the same **time***.

<a name="hr"></a>
### Horizontal Rules

Horizontal rules are made by placing 3 or more hyphens, asterisks, or underscores on a line by themselves.
~~~
---
* * * *
_____________________
~~~
---
* * * *
_____________________

<a name="images"></a>
### Images

Image syntax looks like this:

	![Alt text](/path/to/img.jpg)
	
	![Alt text](/path/to/img.jpg "Optional title")

[!!] Note that the images in userguide are [namespaced](#namespacing).

<a name="tables"></a>
### Tables
~~~
First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell
~~~

First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell

Note that the pipes on the very left and very right side are optional, and you can change the text-alignment by adding a colon on the right, or on both sides for center.
~~~
| Item      | Value | Savings |
| --------- | -----:|:-------:|
| Computer  | $1600 |   40%   |
| Phone     |   $12 |   30%   |
| Pipe      |    $1 |    0%   |
~~~
| Item      | Value | Savings |
| --------- | -----:|:-------:|
| Computer  | $1600 |   40%   |
| Phone     |   $12 |   30%   |
| Pipe      |    $1 |    0%   |
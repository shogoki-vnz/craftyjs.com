<?php
$page = 'tutorials';
include 'header.php';
?>
<div id="wide">
	<h1>Tutorials</h1>
	<p>There is currently four part tutorial to introduce you to the world of Crafty, if you need more help check out the <a href="api/" target="_blank">API</a>
	   or feel free to <a href="contact.php">contact me</a>.</p>
	<p><a href="http://dailyjs.com/2011/02/11/crafty/" target="_blank">Building an RPG</a></p>	<p><a href="http://gitcp.com/sorenbs/jsgames-articles/create-a-game" target="_blank">Creating your first Crafty game</a></p>	<p><a href="https://github.com/starmelt/craftyjstut/wiki" target="_blank">Starmelts Crafty Tutorials</a></p>
	<h2>Part 0: Introduction to Crafty</h2>
    <p>Welcome to the world of Crafty. Crafty is a JavaScript game engine to help you develop games for the browser (and not just the new ones). 
	With a modular design, creating reusable components and extensions is easy and if submitted, could even help others.</p>
	
	<p>Rather than having a long hierarchy of inheritance, Crafty uses the Entity-Component paradigm or data oriented. 
	This will be explained in a later tutorial but is very simple to understand. Basically, every game object is an entity and 
	every bit of functionality is a component. All you need to do to give your entities (or game objects) functionality is to assign some components.</p>
	
	<p>The coding style is loosely based on DOM libraries in a sense that you can select entities by the components assigned and entities methods are chainable.</p>
	<pre><code>Crafty(&quot;2D DOM&quot;).destroy();</code></pre>
	
	<p>The above method uses the selector engine to select all entities with the components 2D and DOM and then destroys the entities found.</p>
	<pre><code>Crafty(&quot;DOM, canvas&quot;).destroy()</code></pre>

	<p>The above selector finds all entities with the components DOM or canvas and destroys it.</p>
	<p>There are numerous extensions and components to help with common problems in games including collision detection, audio, health, gravity, 
	isometric positioning, polygon click maps and much more and developers are strongly encouraged to submit their handy-dandy extensions.</p>

	<h2>Part 1: Entity Component System</h2>
	<p>Entity component systems are very easy to understand and in game development, just make sense. First and foremost, every object in the game is an entity; pure and simple. As soon as you want these entities to do something, then we need components. </p>

<p>Components are just objects that contain properties and methods and once attached to an entity, extend the functionality of that entity. Note, many components can be applied to many entities (a many to many relationship).</p>

<p>Here's an example. We have a player entity. So we create it:</p>

<p><pre><code>var player = Crafty.e();</code></pre></p>

<p>Now we have an entity for player. It doesn't do anything but it exists. Now we have to decide what the entity needs to do and what components can be added that will let us do it. First of all, this entity will need to be drawn on the stage. And for cross browser reasons we want it to be a DOM element. The components needed for this are labelled '2D' which gives the entity x, y, width and height properties and various methods. The other component is called 'DOM' which will automatically draw itself on the stage as a div.</p>

<p>To add these components, simply use the addComponent method (extended from the Crafty core).</p>

<p><pre><code>player.addComponent(&quot;2D, DOM&quot;);</code></pre></p>

<p>We're off to a good start. Only problem is player doesn't look like anything. One basic component we can use is called 'color' which will fill the box of the entity in with a color specified. We want the player to be a red box.
player.addComponent(&quot;color&quot;).color(&quot;red&quot;);
In the last bit of code we added the 'color' component and called it's constructor which is basically a method to initialize the component on the entity with some data (in this case the color we want to use). Notice that the methods are chainable.</p>

<p>Nearly there! One problem we have is that the player dimensions are defaulted to 0. For it to be visible it needs to have some dimensions which are the properties inherited from the '2D' component. To modify the properties we can use the method attr().</p>

<pre><code>player.attr({x: 5, y: 5, w: 50, h: 100});
</code></pre>

<p>Once that method has been executed, the player should now be visible as a red rectangle on the screen, 5 pixels from the top-left corner of the stage, 50 pixels wide and 100 pixels high.
So that is the basics of using the entity component system. Read on for slightly more advanced examples.</p>

	<h2>Part 2: Reusable Components and Extensions</h2>
	<p>Components are crucial to giving your entities functionality and power. The beauty of components in Crafty is that they are reusable in not only your games but any other Crafty game hence why developers are encouraged to give back to the community by submitting components that were very helpful.</p>

<p>In Crafty, components can also be labels meaning adding a component without any definition will simply label it and make it accessible via Crafty selectors even without functionality. An example of this is when using the gravity component; the constructor accepts a component for which it looks for collisions. This component doesn't have to exist other than being attached to an entity.</p>

<p>Let's have a go creating a component that can be used in our games. A common element in games is explosives or mines that explode upon touching it and do damage to the player. Let's make it!</p>

<pre><code>Crafty.c(&quot;explosive&quot;, {

});
</code></pre>

<p>That right there is the skeleton of building a component. The first argument is the ID so that we can identify it to add to an entity. The second argument is an object that will contain properties and methods that the entity will assume.</p>

<p>We want some code to be called as soon as the component is added to an entity, so to do this we add a method called 'init'.</p>

<pre><code>Crafty.c(&quot;explosive&quot;, {
	init: function() {
		if(!this.has(&quot;collision&quot;)) this.addComponent(&quot;collision&quot;);
		this.collision(&quot;player&quot;, function(obj) {
			//hurt player
		});     
	} 
});</code></pre>

<p>The init function will check if the entity has the collision component added, if not it will add it. Then it calls a method inherited from the collision component. The method will run a function whenever there is a collision between the current entity and the one we define in the first argument. We want the explosive to collide with the player which we assume will have a component or label called 'player'.</p>

<pre><code>Crafty.c(&quot;explosive&quot;, {     
	init: function() {
		if(!this.has(&quot;collision&quot;)) this.addComponent(&quot;collision&quot;);      
		this.collision(&quot;player&quot;, function(obj) {            
			if(obj.has(&quot;health&quot;)) {
				obj.hurt(5);
			}
			this.destroy();
		});
	}
});
</code></pre>

<p>When a collision is detected, the object it collides with will be passed as an argument. We check to see if the entity has the health component added. If it does, take away some health points of damage (I arbitrarily chose 5). Then we want the explosive to destroy itself. Once something explodes, it can't explode again, right?</p>

<p>That is a basic component and can be applied to any number of entities and be reusable in multiple games. Easy! Another way to reuse code is to write extensions on the Crafty namespace for code that isn't strictly used by game objects.</p>

<p>The method to extend the namespace is Crafty.extend(). Simply pass it an object with properties and method or even more objects you would like inside the Crafty namespace.</p>

<pre><code>Crafty.extend({
    version: &quot;0.1&quot;
});

Crafty.version; //equals '0.1'</code></pre>

</div>
<?php
include 'footer.php';
?>
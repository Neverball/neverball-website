<?php
if(basename($_SERVER["SCRIPT_NAME"]) == "changelog.php"){
	die();
}
?>

		<h3>New in 1.5.4</h3>
		<ul>
			<li>Fix inconsistent quoting in error messages</li>
			<li>Try to work around GCC optimizations breaking switch/body timers</li>
		</ul>
		<h3>New in 1.5.3</h3>
		<ul>
			<li>Init default most coins times with the level's time</li>
			<li>When scanning for set files, ignore files not ending with &quot;.txt&quot;</li>
			<li>Fix attempt to use &quot;joystick&quot; option before config system is set up</li>
			<li>Add licence info for share/fs_jpg.c, which is based on jdatasrc.c from libjpeg</li>
			<li>Do not flip left/right when using joystick third-axis rotation</li>
			<li>Disallow path separators when entering replay name</li>
			<li>Fix LOCALEDIR ambiguity in Makefile</li>
			<li>Putt: Move shot/desc from courses.txt to the individual course files, a la sets.txt</li>
			<li>Putt: Scan for and add courses not listed in courses.txt after those listed</li>
			<li>Don't clip shadow geometry above ball if &quot;shadow&quot; is set to 3</li>
			<li>Print last error when VFS init fails</li>
			<li>Fix user scores not being read if file has CRLF newlines</li>
			<li>Fix some &quot;glass&quot; materials scheduled as opaque</li>
			<li>Show inapplicable score (e.g. not enough coins for a Fast Unlock) as unqualified rather than leave an empty row</li>
			<li>ufo.map: Replaced uses of red-glass and green-glass</li>
			<li>Removed the now unused green-glass texture</li>
			<li>Fix filler space allocation in GUI</li>
			<li>Correctly apply teleport translation to view position</li>
			<li>maze2.map, runstop.map: replaced pane of glass with a grill (invalidates replays)</li>
			<li>Update &quot;stereo&quot; option description</li>
		</ul>
		<h3>New in 1.5.2</h3>
		<ul>
			<li>French translation update</li>
			<li>Fixed HUD display of selected camera</li>
			<li>Rip out locale &lt;-&gt; UTF-8 filename conversions (and a direct iconv dep)</li>
			<li>Allow view rotation using second gamepad stick</li>
			<li>Remove static replay limit</li>
			<li>Fixed label corners in help screen</li>
			<li>Simplified Chinese translation</li>
			<li>Allow fall back to non-localised font path</li>
			<li>Make fast view rotation modifier configurable, and support joystick</li>
			<li>Squash embarrasing out-of-bounds access bug</li>
			<li>Redo video init when turning on reflections, to fix the long-standing crazy reflections bug</li>
			<li>Middle mouse button toggles between chase/manual views</li>
			<li>ufo.map: Tiny texture fix</li>
			<li>accordian.map: increase time by 5 seconds</li>
			<li>Fix a buffer overflow when reading player name from replay, and support stored names of arbitrary length</li>
			<li>Implement a Quake-like virtual file system with ZIP archive support</li>
			<li>Make sets.txt optional</li>
			<li>Write and look for replays in a Replays directory</li>
			<li>Move replays found at the top of the user dir into Replays</li>
			<li>Keep scores in a Scores directory (migrate old ones, too)</li>
			<li>Write all screen shots to a Screenshots directory</li>
			<li>Set GL read buffer to &quot;front&quot; once and for all when setting video mode</li>
			<li>Actually use the &quot;joystick&quot; config option</li>
			<li>mapc: report unknown materials</li>
			<li>Use separate score files in cheat mode</li>
			<li>Document font requirements</li>
			<li>Increase default view rotation rates</li>
			<li>Load entire font to memory at init</li>
			<li>Add a ball configuration screen</li>
			<li>Added brass-faceted texture to match chrome-faceted</li>
			<li>title.map: Aligned stray coin to the 32-unit grid</li>
			<li>Spanish translation update</li>
			<li>Add normal green texture</li>
			<li>Exclude vertical ball velocity from view computations (no more spinning out of control while in air)</li>
			<li>Added non-reflective texture &quot;blue-wave&quot; for moving water</li>
			<li>Fix typo in French translation</li>
			<li>spacetime.map: Small fixes</li>
			<li>coneskeleton.map: Changed goal_hs times that were based on an outdated goal value</li>
			<li>Remove &quot;--info&quot; command line option</li>
			<li>Include initial view data in the first update (in replays)</li>
			<li>Allow camera switching during ready/set stage</li>
			<li>Add missing sounds in resolution screen</li>
			<li>Tweak selector layout in resolution screen</li>
			<li>Add some space between navigation and set/level selectors</li>
			<li>Make sure the last viewed set selection page actually exists</li>
			<li>Correctly register scores for levels with no time limit</li>
			<li>Remove static level set limit</li>
			<li>Show 6 sets per page</li>
			<li>Paint background first, then mirrors (fix for environments with layers very close to origin)</li>
			<li>Add a Volcano environment (used in levels from Hard 09 to Hard III)</li>
			<li>Add new BGM track6.ogg for use in Volcano environment</li>
			<li>Time to the left, coins to the right in the score board</li>
			<li>Paint score buttons green on a high-score in that score type</li>
			<li>Preempt many buffer overflows related to strncpy usage</li>
			<li>Add label truncation, truncate long labels in several screens</li>
			<li>Clip shadowed geometry above the ball center</li>
			<li>Remove unused Bitstream Vera font</li>
			<li>Revert &quot;Request SSE floating-point math from GCC for x86 systems&quot;</li>
			<li>Updated DejaVu font to v2.29</li>
			<li>Remove archaic &quot;levelname&quot; attribute from maps</li>
			<li>Tweak replay compatibility warning message</li>
			<li>airways.map: split message across two lines</li>
			<li>Do not step state unless it has been painted at least once (avoids &quot;fast-forwards&quot; after long screen loads)</li>
			<li>Change &quot;joystick&quot; default to 1</li>
			<li>Brazilian Portuguese translation</li>
			<li>Remove several uninteresting or problematic balls</li>
			<li>Decrease par to 4 on hole 18 of Tricky Golf</li>
			<li>Change Challenge info area in goal screen to fix breakage using French translation</li>
		</ul>
		<h3>New in 1.5.1</h3>
		<ul>
			<li>Prevent thread race that was occasionally crashing the game</li>
			<li>Rename Unlock Goal scores to Fast Unlock</li>
			<li>Make mouse buttons configurable (full view control)</li>
			<li>Add bindings to toggle between chase and manual views</li>
			<li>Actually load/save joystick dpad config</li>
			<li>Transform default game/locale data paths based on executable name</li>
			<li>Treat a single unrecognised argument as a replay name</li>
			<li>turn.map: full rebuild to prevent a bothering camera problem (invalidates replays)</li>
			<li>bigcone.map: rebuilt bottom part of the cone (invalidates replays)</li>
			<li>German, French, Catalan translation updates</li>
			<li>Init the default fast unlock coin values with the level's goal value</li>
			<li>Display warning on map version mismatch when watching replays.</li>
			<li>ufo.map: Change to reduce stress on the physics engine (invalidates replays)</li>
			<li>Make ENABLE_WII=1 compile again</li>
			<li>adventure.map: Rearranged several coins (invalidates replays)</li>
		</ul>
		<h3>New in 1.5.0</h3>
		<ul>
			<li>Added dictionary elements to SOL file in order to allow metadata storage.</li>
			<li>Reverted short usage in SOL to int.  Was bumping up against the limit.</li>
			<li>Added new textures.</li>
			<li>Corrected various mapping problems in existing maps.</li>
			<li>Tweaked maps.</li>
			<li>Removed 5 set limit.</li>
			<li>Added internationalization.</li>
			<li>Added Catalan, Finnish, French, German, Latvian, Norwegian Nynorsk, and Spanish translations.</li>
			<li>Countless interface tweaks across the board.</li>
			<li>Added three new Neverball sets.</li>
			<li>Reshuffled levels to smooth the difficulty curve.</li>
			<li>Fixed config not being saved sometimes.</li>
			<li>Fixed a misplaced Set Complete screen.</li>
			<li>Split off limited lives and set high-scores to a separate &quot;Challenge&quot; game mode.</li>
			<li>Added bonus levels as a reward in Challenge mode.</li>
			<li>Added four new Neverputt courses.</li>
			<li>Fixed goal sound not being played in replays.</li>
			<li>Removed OSX mouse invert work-around.</li>
			<li>Reworked player name management.</li>
			<li>Added keyboard support for typing text.</li>
			<li>Added an option to launch replays from the command line.</li>
			<li>Added a dialog with replay info in replay selection screen.</li>
			<li>Added Unlock Goal high-scores.</li>
			<li>Made replays use the .nbr filename extension.</li>
			<li>Added invisible switches.</li>
			<li>Fixed layout falling apart due to long replay names.</li>
			<li>Added a new help system.</li>
			<li>Added a HUD toggle, bound to F6.</li>
			<li>Removed coin texture config option, it's now based on locale instead.</li>
			<li>Display current camera type when starting a level.</li>
			<li>Save screenshots as PNG instead of BMP.</li>
			<li>Implemented collectible grow/shrink items.</li>
			<li>Added ability to restart a level mid-game (normal mode only), bound to R.</li>
			<li>Added SVG icons.</li>
			<li>Fixed replays not being overwritten on Windows.</li>
			<li>Increased mapc limits.</li>
			<li>Fixed a repeated path inaccuracy.</li>
			<li>Fixed mute sounds after toggling the audio setting in config screen.</li>
			<li>Added multisample option.</li>
			<li>Fixed ball texture seem.</li>
			<li>Fixed GUI font texture coordinates sometimes being off by half a pixel.</li>
			<li>Made ball bounce more realistically with respect to moving objects.</li>
			<li>Replaced all TGA files with PNGs.</li>
			<li>Made several key bindings only available in development mode.</li>
			<li>Removed MSVC support, only MinGW is supported.</li>
			<li>Store user config in %APPDATA%\Neverball on Windows.</li>
			<li>Bound camera rotation keys to S and D by default.</li>
			<li>Added new pause screen.</li>
			<li>Changed lights to evenly illuminate entire maps.</li>
			<li>Fixed not being able to deactivate a timed switch on at start of level.</li>
			<li>Added platform acceleration toggle.</li>
			<li>Added Neverputt keyboard navigation.</li>
			<li>Added decal material flag in order to enable decals coincident with base geometry.</li>
			<li>Added a tweak to place the GUI into &quot;recently moved&quot; mode upon level end.  This will force the player to recenter the joystick before the GUI with work.  Thus, the default button will not be accidentally deselected if the joystick is not centered when play ends.</li>
			<li>Changed mover OBJ mechanism.</li>
			<li>Removed ball shadow in poser mode.</li>
			<li>Rewrote audio code without SDL_mixer dependency to eliminate annoying crackle on various platforms.</li>
			<li>Removed audio_rate config variable.</li>
			<li>Rewrote image handling, eliminating SDL_image.</li>
			<li>Added mipmap and anisotropic options.</li>
			<li>Fixed level data not being freed by conf state.  This allowed OpenGL state to leak when the context is bounced on resolution change.</li>
			<li>Fixed image_white not saturating red channel on RGB and RGBA images.</li>
			<li>Modified material sorter to draw opaque decals AFTER opaque textures, and transparent decals BEFORE transparent textures.</li>
			<li>Removed TGA search from mapc.</li>
			<li>Added lump smoothing to mapc.</li>
			<li>Added glassy effect to glass materials.</li>
			<li>Some OpenGL optimization and state-change reduction.</li>
			<li>Added foreground billboards.</li>
			<li>Fixed the zero-velocity test in the edge/vert collision detection. Was producing different results with different compilers.</li>
			<li>Added constant DT.</li>
			<li>Modified FPS calculation to be more correct.</li>
			<li>Added stats collection and output.</li>
			<li>Added application controlled vblank sync.</li>
			<li>Enhanced specular illumination.</li>
			<li>Added tilt sensor abstraction.</li>
			<li>Added Wiimote tilt sensor mode for Linux.</li>
			<li>Fixed empty buttons possibly being layed out so small that the rounding rectangle overlaps itself.</li>
			<li>Added joystick digital pad button config symbols.</li>
			<li>Fixed failure to load ball texture breaks shadow texture.</li>
			<li>Added new ball rendering mechanism.</li>
			<li>Added several new balls using the new mechanism.</li>
			<li>Ignored mouse motion events generated on mouse grab, sometimes causing sudden tilting to bottom right at level start.</li>
			<li>Texture cleanup.</li>
			<li>Added uniform sync option.</li>
			<li>Fixed Neverputt using hard-coded limits for score counts.</li>
			<li>Added curve tool to distribution.</li>
			<li>Implemented new shot name generation using a persistent index.</li>
			<li>Now saving screenshots in user data directory.</li>
			<li>Added support for decimal fractions in mapc.</li>
			<li>Rebuilt curves in maps to take advantage of decimal fractions.</li>
			<li>Allowed spaces in config values.</li>
			<li>Greatly optimized several mapc operations.</li>
			<li>Fixed texture rotation in mapc.</li>
			<li>Used OpenGL memory layout conventions for storing image data, allowing proper texture coordinates to be used in OBJs and elsewhere.</li>
			<li>Moved goal detection factor from code to Neverputt maps.</li>
			<li>Set the EWMH _NET_WM_ICON window hint on X11 systems (WM icons with 8-bit alpha transparency).</li>
			<li>Delayed buffer swap in level_snap until after image_snap, following OpenGL standard and fixing off-by-one type error in set shots on some ATI hardware.</li>
			<li>Eliminated performance penalty on mirror-less maps with reflections enabled.</li>
			<li>Made default replay name user-configurable.</li>
			<li>Fixed mapc to avoid overflows when operating on SSE hardware.</li>
			<li>Fixed logic of BSP back/front tests.</li>
			<li>Requested SSE floating-point math from GCC for x86 systems</li>
			<li>Redesigned teleporter visuals.</li>
			<li>Implemented a server/client-like game/replay architecture.</li>
		</ul>
		<h3>New in 1.4.0</h3>
		<ul>
			<li>Fixed font renderer to never exceed maximum texture size.</li>
		</ul>
		<h3>New in 1.3.11</h3>
		<ul>
			<li>Fixed broken Unix build.</li>
			<li>Added OSX SDL mouse invert workaround.</li>
			<li>A few texture tweaks.</li>
			<li>Fixed broken Ball HUD.</li>
		</ul>
		<h3>New in 1.3.10</h3>
		<ul>
			<li>Tweaks to pars.</li>
			<li>Added wireframe view to Putt.</li>
			<li>Fixed view toward hole when the hole is directly above or below the ball.</li>
		</ul>
		<h3>New in 1.3.9</h3>
		<ul>
			<li>Made a few minor fixes to paxed's holes.</li>
			<li>Added score and par to HUD.</li>
			<li>Show score card only after a hole is finished.</li>
			<li>Added timeouts to all in-game states except Next Player.</li>
			<li>Fixed texture ID leak in conf state.</li>
		</ul>
		<h3>New in 1.3.8</h3>
		<ul>
			<li>Modified joystick traversal of GUIs to wrap top/bottom and left/right.</li>
			<li>Converted Putt to use Ball's GUI API.</li>
			<li>Merged Putt's and Ball's audio managers into shared.</li>
			<li>Added &quot;Paused&quot; screen to Putt.</li>
			<li>Generalized Putt's scoring to allow courses of length other than 18.</li>
			<li>Enabled switches in Putt.</li>
			<li>Added paxed's Putt courses.</li>
			<li>Changed a few textures on paxed's courses.</li>
			<li>Capped per-hole scores at 12.</li>
			<li>Modified several Putt backgrounds.</li>
			<li>Applied a change to Mehdi 25.</li>
			<li>Added poser mode to Putt.</li>
			<li>&quot;Fixed&quot; mouse jump on pointer grab.  SDL is unpredictable here.</li>
			<li>By popular demand, set initial ball view toward hole.</li>
		</ul>
		<h3>New in 1.3.7</h3>
		<ul>
			<li>Fixed mapc normal optimization bug.  Resolves the Putt 16 invisible wall.</li>
		</ul>
		<h3>New in 1.3.6</h3>
		<ul>
			<li>Fixed mapc normal optimization bug.  Resolves the &quot;Level 6&quot; crash.</li>
		</ul>
		<h3>New in 1.3.5</h3>
		<ul>
			<li>Applied fixes to Mehdi's levels</li>
			<li>Fixed GUI keyboard caps lock bug.</li>
			<li>Fixed no default GUI state on Done screen (joystick didn't work).</li>
			<li>Added fast camera rotation bound to Shift keys.</li>
			<li>Added camera rotation rate to config file.</li>
			<li>Modified perspective matrix to match gluPerspective.</li>
		</ul>
		<h3>New in 1.3.4</h3>
		<ul>
			<li>Removed unnecessary autopause when pointer is not grabbed.</li>
			<li>Fixed GUI not hilighting widget under cursor when mouse isn't moved.</li>
			<li>Fixed config file written only when changed.</li>
		</ul>
		<h3>New in 1.3.3</h3>
		<ul>
			<li>Removed few remaining bits of GLU code.</li>
			<li>Whitened glyph textures manually rather than relying upon pixel bias.</li>
			<li>Fixed Neverputt shadow</li>
		</ul>
		<h3>New in 1.3.2</h3>
		<ul>
			<li>Rewrote shadow code to use mulipass rather than multitexture.</li>
			<li>Removed all multitexture code.</li>
			<li>Added shadow option to config screen.</li>
			<li>Fixed music fade-in bug.</li>
			<li>Fixed badly named static variable &quot;clock&quot;.</li>
			<li>Added level number to replay save screen.</li>
		</ul>
		<h3>New in 1.3.1</h3>
		<ul>
			<li>Fixed Save Replay not allowing score count to complete.</li>
			<li>Fixed bad unlock score in Mehdi 25.</li>
		</ul>
		<h3>New in 1.3.0</h3>
		<ul>
			<li>Fixed replay header nonportable.</li>
		</ul>
		<h3>New in 1.2.11</h3>
		<ul>
			<li>Rewrote SOL reader/writer to remove byte-order dependance.</li>
			<li>Rewrote replay handler to remove byte-order dependance</li>
			<li>Modified replay selector to ignore partial replays.</li>
			<li>Added camera mode gamepad control.</li>
			<li>Applied Mehdi's updates to several levels.</li>
			<li>Generalized config string handling.  Added coin and ball option strings.</li>
			<li>Added two new background music tracks.</li>
			<li>Fixed broken set scoring.</li>
			<li>Added Set Record screen.</li>
		</ul>
		<h3>New in 1.2.10</h3>
		<ul>
			<li>Added caps lock key to keyboard.</li>
			<li>Worked around Neverputt overwriting Neverball's camera setting.</li>
			<li>Added clobber confirmation to replay save.</li>
			<li>Fixed an audio bug when fading to the currently playing song.</li>
		</ul>
		<h3>New in 1.2.9</h3>
		<ul>
			<li>Merged set-complete state into goal state, fixing set-complete crash.</li>
			<li>Fixed a few game state init crashes.</li>
			<li>Fixed a few pointer grab issues.</li>
			<li>Added random replay during attract mode.</li>
			<li>Added scene fade in/out.</li>
			<li>Fixed a bug causing the texture quality setting to be ignored.</li>
			<li>Moved shadow handling in with other geometry, where it belongs.</li>
			<li>Moved particle and shadow init/free out of game init/free and into config.</li>
		</ul>
		<h3>New in 1.2.8</h3>
		<ul>
			<li>Improved directory handling.  No longer depends on CWD.</li>
			<li>Moved all config files to ~/.neverball/ directory.</li>
			<li>Screenshots now go to CWD, which is not changed.</li>
			<li>Reorganized replay handling.</li>
			<li>Added replay save / play / delete GUIs.</li>
			<li>Added auto disabling of stereo and reflection is mode set fails.</li>
			<li>Changed default pointer state to ungrabbed.</li>
		</ul>
		<h3>New in 1.2.7</h3>
		<ul>
			<li>Changed policy: Goal opens after a set number of coins are collected.</li>
			<li>Changed policy: Extra balls are awarded only after a goal.</li>
			<li>Changed policy: Game is saved after every goal.</li>
			<li>Changed hud to reflect new scoring policies.</li>
			<li>Fixed volume set bug.</li>
			<li>Fixed shadow visible on reflective surfaces in level shots.</li>
			<li>Fixed music on config screen.</li>
			<li>Increased ball transparancy to accomodate new view.</li>
			<li>Added subtle view distance flexibility.</li>
			<li>Merged Goal and High Score states.</li>
			<li>Tweaked some levels and scores in line with new scoring policies.</li>
			<li>Added better fading to make music less repetitious and annoying.</li>
		</ul>
		<h3>New in 1.2.6</h3>
		<ul>
			<li>Modularized state functions.</li>
			<li>Changed view to make horizon visible.</li>
			<li>Added view configuration options for people who whine about the new view.</li>
			<li>Changed background images to augment horizon.</li>
			<li>Fixed Neverputt far clip set closer than background.</li>
			<li>Fixed an audio init bug.</li>
			<li>Generalized config handling.</li>
			<li>Heavily modified GUI handling.</li>
			<li>Replaced all 2D menus with new GUI code.</li>
			<li>Changed image loading policy to support non-power-of-two images.</li>
			<li>Replaced Win32 makefile with VS.Net solution.</li>
			<li>Rewrote HUD to use new GUI handler.</li>
			<li>Added billboard objects to .SOL file.</li>
			<li>Policy change: .SOL files now go in same directory as .MAP files.</li>
			<li>Changed mapc to derive .SOL file name from .MAP file name.</li>
			<li>Changed pause screen to include the word &quot;Paused&quot;.</li>
			<li>Changed internal clock to use integer deciseconds instead of float seconds. (HIGH SCORE FILE CHANGED)</li>
			<li>Made some fixes to reflection handling.</li>
			<li>Added background .SOLs.</li>
			<li>Added wireframe mode.</li>
			<li>Added look-around mode.</li>
			<li>Added keyboard-to-joystick input mapping.</li>
			<li>Added mipmap generation.</li>
			<li>Added clamped material type.</li>
		</ul>
		<h3>New in 1.2.5</h3>
		<ul>
			<li>Fixed refrected background rotation.</li>
			<li>Added OBJ loading to mapc.</li>
			<li>Finally fixed material hack in mapc.</li>
			<li>A few graphical optimizations.</li>
			<li>Added shadow config option.</li>
			<li>Added sphere-map material type.</li>
			<li>Modified many levels to use detail OBJs.</li>
			<li>Traded doubles/ints for floats/shorts in the .sol file.  This cuts .sol file size in half, but puts a limit on level complexity.</li>
		</ul>
		<h3>New in 1.2.4</h3>
		<ul>
			<li>Added camera control key binding to config file.</li>
			<li>Fixed some braindead reflection handling code.</li>
			<li>Fixed broken config menu.</li>
			<li>Fixed sound volume adjustment crash when sound is disabled.</li>
			<li>Fixed very stupid game timing bug that had been around far too long.</li>
		</ul>
		<h3>New in 1.2.3</h3>
		<ul>
			<li>Added reflection material.</li>
			<li>Modified several levels to use reflective material.</li>
			<li>Added option to disable reflection materials.</li>
			<li>Updated mapping documentation.</li>
			<li>Changed shadow CLAMP_TO_EDGE to CLAMP to work around some bad drivers.</li>
			<li>Added arrow key bindings for camera rotation.</li>
		</ul>
		<h3>New in 1.2.2</h3>
		<ul>
			<li>Fixed a sneaky bug in menu memory that allowed unopened levels to be played.</li>
			<li>Fixed camera rotation joystick button init bug.</li>
			<li>Changed options menu to make unavailable modes unselectable.</li>
			<li>Implemented auto-pause when the game loses focus externally.</li>
			<li>Simplified ARB extension handling.</li>
			<li>Made another tweak to the level set screenshot loader.</li>
			<li>Applied Mehdi's tweak to level 5.</li>
		</ul>
		<h3>New in 1.2.1</h3>
		<ul>
			<li>Fixed the scoring bug for real this time.</li>
			<li>Documented global set scoring.</li>
			<li>Added a ball &quot;ghost&quot; to ensure the ball remains visible when obscured.</li>
		</ul>
		<h3>New in 1.2.0</h3>
		<ul>
			<li>Fixed the path timing bug for real this time.</li>
		</ul>
		<h3>New in 1.1.6</h3>
		<ul>
			<li>Updated Mehdi's set to final.</li>
			<li>Fixed a path timing bug to correct a slight path pause discrepancy.</li>
			<li>Fixed scoring bug that was adding coins from failed level to global score.</li>
			<li>Fixed a bug corrupting level set screenshots.</li>
		</ul>
		<h3>New in 1.1.5</h3>
		<ul>
			<li>Reorganized code, merged Neverputt code.</li>
			<li>Added friction physics.</li>
			<li>Fixed goal particle radius.</li>
		</ul>
		<h3>New in 1.1.4</h3>
		<ul>
			<li>Set all menus to remember their last selection.  Now the frustrated player need only pound angrily on the mouse button to get back to his last save.</li>
		</ul>
		<h3>New in 1.1.3</h3>
		<ul>
			<li>Fixed pipe.sol dependancy missing from Win32 makefile.</li>
			<li>Added help screen.</li>
		</ul>
		<h3>New in 1.1.2</h3>
		<ul>
			<li>Mouse invert and joystick select patches.</li>
			<li>Minor level tweaks.</li>
			<li>Fixed no levels open after reading old high score file.</li>
			<li>Fixed activation of all switches at the same location.</li>
			<li>Added camera HUD indicator.</li>
		</ul>
		<h3>New in 1.1.1</h3>
		<ul>
			<li>Added stereo viewing.</li>
			<li>Fixed Win32 makefile.</li>
		</ul>
		<h3>New in 1.1.0</h3>
		<ul>
			<li>Minor tweaks and cleanup for an announced release.</li>
		</ul>
		<h3>New in 1.0.5</h3>
		<ul>
			<li>Changed default scores file format, removing player names.</li>
			<li>Adjusted level order and default scores.</li>
			<li>Reimplemented physics lock punt.</li>
		</ul>
		<h3>New in 1.0.4</h3>
		<ul>
			<li>New levels</li>
			<li>Fixed texture positioning bug</li>
			<li>Darkened screen during pause</li>
			<li>Modified high scores to keep global highs.  (HIGH SCORE FILE CHANGED)</li>
			<li>Added new automatic level shot grabber.</li>
			<li>Tweaked BSP optimizer.</li>
		</ul>
		<h3>New in 1.0.3</h3>
		<ul>
			<li>Modified some textures.</li>
			<li>Modified some levels.</li>
			<li>Modified switches to switch entire path chains.</li>
			<li>Disallowed player from toggling timed switches off manually.</li>
		</ul>
		<h3>New in 1.0.2</h3>
		<ul>
			<li>Added level set selector.  (HIGH SCORE FILE CHANGED)</li>
			<li>Modified demo playback to handle level sets.</li>
			<li>Added BSP optimization to physics.</li>
			<li>New textures.</li>
			<li>New levels.</li>
			<li>Added Mehdi's level set.</li>
			<li>Modified switches to act on paths rather than bodies.</li>
			<li>Added timer option to switches.</li>
		</ul>
		<h3>New in 1.0.1</h3>
		<ul>
			<li>Modified physics to better handle vertical movers.</li>
			<li>Added mover switch entity.</li>
			<li>Replaced level 13 with a level using vertical movers and switches.</li>
		</ul>
		<h3>New in 1.0.0</h3>
		<ul>
			<li>Absolutely nothing</li>
		</ul>
		<h3>New in 0.25.12</h3>
		<ul>
			<li>FreeBSD support</li>
			<li>OSX support</li>
		</ul>
		<h3>New in 0.25.11</h3>
		<ul>
			<li>Changed platforms to accelerate rather than change velocity instantly.</li>
			<li>Changed timer to display minutes.  Increased max time from 99s to 9m59s.</li>
			<li>Changed teleport exit to be relative to teleport entry.</li>
			<li>Fixed time comparison ambiguity.</li>
		</ul>
		<h3>New in 0.25.10</h3>
		<ul>
			<li>Fix broken load balancer starving the renderer on early level load.</li>
			<li>Disabled music playback entirely when music volume is zero.</li>
		</ul>
		<h3>New in 0.25.9</h3>
		<ul>
			<li>Demo record and replay.</li>
		</ul>
		<h3>New in 0.25.8</h3>
		<ul>
			<li>Padded .sol files to make them portable between Linux and Windows.</li>
			<li>Added pulsing HUD numbers.</li>
			<li>Changed Windows config file path.</li>
			<li>Changed pause mode to continue rendering.</li>
		</ul>
		<h3>New in 0.25.7</h3>
		<ul>
			<li>Added background music.</li>
			<li>Changed several sounds.</li>
			<li>Added sound and music volume control to options screen and config file.</li>
			<li>Fixed discrepancy between reported time and recorded time.</li>
			<li>Fixed new coin record not triggering name input state.</li>
			<li>Fixed ESC during goal state.</li>
		</ul>
		<h3>New in 0.25.6</h3>
		<ul>
			<li>Level score and high score list added to goal screen.</li>
			<li>Level score added to record screen.</li>
			<li>Goal screen requires click-through instead of time-out.</li>
			<li>Fixed level time bug.  Clock was running during goal screen.</li>
		</ul>
		<h3>New in 0.25.5</h3>
		<ul>
			<li>Player name stored in config.</li>
			<li>Keyboard camera selection.</li>
			<li>Tweaked cameras.</li>
		</ul>
		<h3>New in 0.25.4</h3>
		<ul>
			<li>Changed save game policy: it must be earned by collecting coins.</li>
			<li>Added camera tracking configuration option.</li>
			<li>Added coin sub-sort of time records.</li>
			<li>Added time sub-sort of coin records.</li>
			<li>Fixed exit-during-teleport bug.</li>
			<li>Fixed potential infinite loop when ball is crushed.</li>
			<li>Warn and continue on audio init failure.</li>
		</ul>
		<h3>New in 0.25.3</h3>
		<ul>
			<li>Modified camera tracking, disabled direct camera control.</li>
		</ul>
		<h3>New in 0.25.2</h3>
		<ul>
			<li>Added default records.  Something to shoot for.</li>
		</ul>
		<h3>New in 0.25.1</h3>
		<ul>
			<li>Added record keeping.</li>
			<li>Reorganized level selector to include records.</li>
			<li>Added record name input state.</li>
			<li>Changed shadow clamp back to CLAMP_TO_EDGE.  Screw broken drivers.</li>
			<li>Changed timer to display hundredths of seconds.</li>
			<li>Got rid of clock tick until last 10 seconds (it conflicts with music).</li>
			<li>Returned original menu pointer motion.  Reorganized point hide.</li>
			<li>Globalized pause state to generalize pointer grab handling.</li>
		</ul>
		<h3>New in 0.25.0</h3>
		<ul>
			<li>Added teleportation.</li>
			<li>Changed menus to work with relative pointer motion.</li>
			<li>Changed pointer grab policy to lessen grab motion discontinuity impact.</li>
			<li>Fixed channel order for TGA textures.</li>
			<li>Fixed level selector link topology.</li>
			<li>New levels.</li>
		</ul>
		<h3>New in 0.21.0</h3>
		<ul>
			<li>Display screen shots in level selector.</li>
			<li>Cut level selector to 25 to make room for level shot.</li>
			<li>Generalized flyby representation.</li>
			<li>Added poser state for capturing level shots.</li>
			<li>New Levels.</li>
		</ul>
		<h3>New in 0.16.2</h3>
		<ul>
			<li>Native Windows support returned.</li>
			<li>Fixed HUD not responding to texture quality setting.</li>
			<li>Hyper-paranoid ARB_multitexture usage.</li>
		</ul>
		<h3>New in 0.16.0</h3>
		<ul>
			<li>Fixed input smoothing in game_step causing oscillation at low FPS.</li>
			<li>Worked around broken shadow CLAMP_TO_EDGE on i845G.</li>
			<li>Added high_level config and modified level select.  Documented cheat.</li>
			<li>New levels.</li>
		</ul>
		<h3>New in 0.14.0</h3>
		<ul>
			<li>New version numbering convention.  Minor number is level count.</li>
			<li>Broke native Windows support.</li>
			<li>Reorganized source and Makefiles.</li>
			<li>Modified mapc to take a materials path.</li>
			<li>Fixed potential overflow in config_home.</li>
		</ul>
		<h3>New in 0805b</h3>
		<ul>
			<li>Worked around broken color mask attribute pop in ball_draw on ATI.</li>
		</ul>
		<h3>New in 0805a</h3>
		<ul>
			<li>Sent screenshots to $HOME rather than CWD, fixing segfault.</li>
		</ul>
		<h3>New in 0805</h3>
		<ul>
			<li>Removed some initial state assumptions that were wrong for some users.</li>
			<li>Fixed $HOME determination under Windows.</li>
		</ul>
		<h3>New in 0804</h3>
		<ul>
			<li>First release as &quot;Neverball&quot;.</li>
			<li>Added joystick control.</li>
			<li>Added camera control.</li>
			<li>Added ball shadow.</li>
			<li>Added level intros.</li>
			<li>Added config file stored in $HOME.</li>
			<li>Removed text images and added TTF rendering.</li>
			<li>New textures.</li>
			<li>New levels.</li>
		</ul>
		
function get_json(url, callback) {
        var req = new XMLHttpRequest();
        req.onreadystatechange = function() {
                if (req.readyState == 4 && req.status == 200) {
                        callback(JSON.parse(req.responseText));
                }
        }
        req.open("GET", url, true);
        req.send();
}

window.onload = function() {
	var path_name = window.location.pathname;
        var c = document.getElementById("map");
        var ctx = c.getContext("2d");

        ctx.fillStyle = "lightgrey";
        ctx.fillRect(0, 0, c.width, c.height);

	function render_map(world) {
		var i;
	        for (i = 0; i < world.length; i += 1) {
	                ctx.fillStyle = world[i].colour;
	                ctx.fillRect(world[i].x, world[i].y, world[i].width, world[i].height);
	        }
	}
	
	get_json(path_name + "?command=map", render_map);
	function render_mario(position, frame) {
		frame = frame % 12;
		img = new Image();
		var frame_string = frame.toString();
		if (frame_string.length < 2) {
			frame_string = "0" + frame_string;
		}
		img.src = "arts/mario" + frame_string + "s.png";
		img.onload = function() {
	                ctx.fillStyle = position.colour;
	                ctx.fillRect(position.x - 32, position.y - 32, 64, 64);
			ctx.drawImage(img, position.x - 32, position.y - 32);
		};
		setTimeout(function() {
			render_mario(position, frame + 1);
		}, 100);
	}
	get_json(path_name + "?command=where", function(position) {
		render_mario(position, 0);
	});
		
};


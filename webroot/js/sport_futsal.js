//
// Futsal-specific functions
//

function maxLength() { return 40; }
function defaultLength() { return 25; }
function minLength() { return 20; }
function maxWidth() { return 25; }
function defaultWidth() { return 15; }
function minWidth() { return 15; }

function boxLength(length)
{
	return Math.min(length * 0.25, 18);
}

function boxWidth(width)
{
	return Math.min(width * 0.75, 44);
}

function circleRadius(length)
{
	return Math.min((length - 2 * boxLength(length)) * 0.2, 10);
}

function layoutText(id)
{
	return null;
}

function outlinePositions(id)
{
	var position = fields[id].marker.getPosition();

	var bb = new Array;
	var side = makePosition(position, fields[id].width / 2, 180 - fields[id].angle);
	bb[0] = makePosition(side, fields[id].length / 2, 270 - fields[id].angle);
	bb[1] = makePosition(bb[0], fields[id].width, 0 - fields[id].angle);
	bb[2] = makePosition(bb[1], fields[id].length, 90 - fields[id].angle);
	bb[3] = makePosition(bb[2], fields[id].width, 180 - fields[id].angle);
	return bb;
}

function inlinePositions(id)
{
	var box_length = boxLength(fields[id].length);
	var box_width = boxWidth(fields[id].width);
	var position = fields[id].marker.getPosition();

	var bb = new Array;

	bb[0] = new Array;
	bb[0][0] = makePosition(position, fields[id].width / 2, 180 - fields[id].angle);
	bb[0][1] = makePosition(bb[0][0], fields[id].width, 0 - fields[id].angle);

	bb[1] = new Array;
	var goal = makePosition(position, fields[id].length / 2, 90 - fields[id].angle);
	bb[1][0] = makePosition(goal, box_width / 2, 180 - fields[id].angle);
	bb[1][1] = makePosition(bb[1][0], box_length, 270 - fields[id].angle);
	bb[1][2] = makePosition(bb[1][1], box_width, 0 - fields[id].angle);
	bb[1][3] = makePosition(bb[1][2], box_length, 90 - fields[id].angle);

	bb[2] = new Array;
	var goal = makePosition(position, fields[id].length / 2, 270 - fields[id].angle);
	bb[2][0] = makePosition(goal, box_width / 2, 180 - fields[id].angle);
	bb[2][1] = makePosition(bb[2][0], box_length, 90 - fields[id].angle);
	bb[2][2] = makePosition(bb[2][1], box_width, 0 - fields[id].angle);
	bb[2][3] = makePosition(bb[2][2], box_length, 270 - fields[id].angle);

	bb[3] = makeCircle(position, circleRadius(fields[id].length));

	return bb;
}

function updateForm()
{
	jQuery('#show_angle').html(fields[current].angle);
	jQuery('#show_width').html(fields[current].width);
	jQuery('#show_length').html(fields[current].length);
}

function saveField()
{
	if (current != 0) {
		fields[current].angle = parseInt (jQuery('#show_angle').html());
		fields[current].width = parseInt (jQuery('#show_width').html());
		fields[current].length = parseInt (jQuery('#show_length').html());
	}
}

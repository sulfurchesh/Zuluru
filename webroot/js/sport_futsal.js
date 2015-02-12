//
// Futsal-specific functions
//

function futsalMaxLength() { return 40; }
function futsalDefaultLength() { return 25; }
function futsakMinLength() { return 20; }
function futsalMaxWidth() { return 25; }
function futsakDefaultWidth() { return 15; }
function futsalMinWidth() { return 15; }

function futsalBoxLength(length)
{
	return Math.min(length * 0.25, 18);
}

function futsalBoxWidth(width)
{
	return Math.min(width * 0.75, 44);
}

function futsalCircleRadius(length)
{
	return Math.min((length - 2 * futsalBoxLength(length)) * 0.2, 10);
}

function futsalLayoutText(id)
{
	return null;
}

function futsalOutlinePositions(id)
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

function futsalInlinePositions(id)
{
	var box_length = futsalBoxLength(fields[id].length);
	var box_width = futsalBoxWidth(fields[id].width);
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

	bb[3] = makeCircle(position, futsalCircleRadius(fields[id].length));

	return bb;
}

function futsalUpdateForm()
{
	jQuery('#soccer_fields #show_angle').html(fields[current].angle);
	jQuery('#soccer_fields #show_width').html(fields[current].width);
	jQuery('#soccer_fields #show_length').html(fields[current].length);
}

function futsalSaveField()
{
	if (current != 0) {
		fields[current].angle = parseInt (jQuery('#soccer_fields #show_angle').html());
		fields[current].width = parseInt (jQuery('#soccer_fields #show_width').html());
		fields[current].length = parseInt (jQuery('#soccer_fields #show_length').html());
	}
}

<?php

function get_release_info($repo)
{
	$info = GitHubRelease::RequestLatest($repo);
	return [
		"version" => $info->version,
		"download" => $info->download,
	];
}

$superscrot = get_release_info("horsedrowner/Superscrot");

$projects = [
	[
		"name" => "Superscrot",
		"description" => "A better screenshot uploader.",
		"status" => "Passive development",
		"version" => $superscrot["version"],
		"download" => $superscrot["download"],
		"source" => "https://github.com/horsedrowner/Superscrot",
	],
	[
		"name" => "Media Key Simulator",
		"description" => "A small program that simulates media keys when pressing certain key combinations.",
		"status" => "Completed",
		"version" => "1.0",
		"source" => "https://github.com/horsedrowner/MediaKeySimulator"
	],
	[
		"name" => "Image to UML",
		"description" => "This project is an assignment for the Software Engineering course at LIACS, Leiden University. The objective of the project is to develop tools for the conversion of images of UML class diagrams in common graphical formats (e.g., BMP, JPEG, GIF, etc.) to structured UML class diagrams stored e.g., in XMI format to enable their analysis with UML design tools. </p><p> The assignment was done in a group with <a hreflang=\"nl\" href=\"http://www.liacs.nl/~tkappe/\">Tobias Kapp&eacute;</a> and <a hreflang=\"nl\" href=\"http://www.liacs.nl/~plouchtc/\">Philipe Louchtch</a>.",
		"status" => "Completed",
		"source" => "http://code.google.com/p/image-to-uml/source/browse/#svn%2FLeiden%2FGroup6",
		"customAttributes" => [
			"Grade" => "9.5/10",
		],
	],
];

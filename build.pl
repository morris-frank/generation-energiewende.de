#!/usr/bin/perl

use strict;
use warnings;
use File::Basename;
use Term::ANSIColor;
use File::Copy;

my $dir_content = 'content/';
my $dir_build = 'build/';
my $dir_src = 'src/';

process_content_files();
process_sass();
exit;

sub process_sass {
    print colored( '    Processing SASS', 'yellow' ), "\n";
    system("node_modules/.bin/sass $dir_src/main.sass $dir_build/main.css");
    print colored( 'Processed SASS', 'green' ), "\n";
}

sub process_content_files {
    my @input_list = glob "$dir_content*html";

    foreach my $input_file (@input_list) {
        process_content_file($input_file);
    }
    print colored( "Processed all content", 'green' ), "\n";
}

sub process_content_file {
    my ($input_file) = @_;
    my $basename = basename($input_file, ".html");
    print colored( "    Processing $basename", 'yellow' ), "\n";

    my $content = read_file($input_file);
    my $output = read_file($dir_src . 'skeleton.html');

    my $title = "";
    if ($content =~ m/<h1[^>]*>([^<]+)<\/h1>/i ) {
        $title = $1;
    } else {
        $title = $basename;
    }

    $output =~ s/\{\{CONTENT\}\}/$content/g;
    $output =~ s/\{\{TITLE\}\}/$title/g;

    my $output_file = "";
    if ($basename eq "index") {
        $output_file = "$dir_build$basename.html";
    } else {
        mkdir($dir_build . $basename);
        $output_file = "$dir_build$basename/index.html";
    }
    write_file($output_file, $output);
    system("node_modules/.bin/js-beautify $output_file > $output_file.bak");
    move("$output_file.bak", $output_file);
}

sub read_file {
    my ($filename) = @_;

    open my $in, '<:encoding(UTF-8)', $filename or die "Could not open '$filename' for reading $!";
    local $/ = undef;
    my $all = <$in>;
    close $in;

    return $all;
}

sub write_file {
    my ($filename, $content) = @_;

    open my $out, '>:encoding(UTF-8)', $filename or die "Could not open '$filename' for writing $!";;
    print $out $content;
    close $out;

    return;
}

<?php
namespace kahlan\spec\suite\matcher;

use kahlan\matcher\ToBeNull;

describe("toBeNull", function() {

    describe("::match()", function() {

        it("passes if null is null", function() {

            expect(null)->toBeNull();

        });

        it("fails if false is null", function() {

            expect(false)->not->toBeNull();

        });

        it("fails if [] is null", function() {

            expect([])->not->toBeNull();

        });

        it("fails if 0 is null", function() {

            expect(0)->not->toBeNull();

        });

        it("fails if '' is null", function() {

            expect('')->not->toBeNull();

        });

    });

    describe("::description()", function() {

        it("returns the description message", function() {

            $report['params'] = [
                'actual'   => 2
            ];

            $actual = ToBeNull::description($report);

            expect($actual)->toBe('be null.');

        });

    });

});

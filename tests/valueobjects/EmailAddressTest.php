<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/28/2019
 * Time: 22:09
 */
declare(strict_types=1);
namespace testscase\valueobjects;

use PHPUnit\Framework\TestCase;
use youvids\data\valueobj\EmailAddress;

/**
 * Class EmailAddress
 * @package tests\valueobjects
 */
class EmailAddressTest extends TestCase {

    /**
     * @test
     */
    public function testCanBeCreatedFromValidEmailAddress(): void {

        $this->assertInstanceOf(
            EmailAddress::class,
            new EmailAddress("test@example.com")
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void {

        $this->expectException(\InvalidArgumentException::class);
        $email = new EmailAddress("this_is_not_a_valid_email");
    }

//    public function testCanBeUsedAsString(): void {
//
//        $this->assertEquals(
//            "test@example.com",
//            EmailAddress::fromString("user@example.com")
//        );
//    }

    public function testRequireEmailAddress() {

        $this->expectException(\Exception::class);
        $email = new EmailAddress;
    }

    /**
     * @test Testing immutability
     */
    public function testCopiedEmailAddressShouldRepresentSameValue()
    {
        $email = new EmailAddress("test@domain.com");
        $copiedEmail = EmailAddress::fromSelf($email);
        $this->assertTrue($email->equals($copiedEmail));
    }
}
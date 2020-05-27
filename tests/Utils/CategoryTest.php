<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;
    protected $mockedCategoryTreeAdminList;
    protected $mockedCategoryTreeAdminOptionList;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $urlgenerator = $kernel->getContainer()->get('router');
        $testedClasses = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionList',
            'CategoryTreeFrontPage'
        ];

        foreach ($testedClasses as $class) {
            $name = 'mocked' . $class;
            $this->$name = $this->getMockBuilder('App\Utils\\' . $class)
                ->disableOriginalConstructor()
                ->setMethods() // if no, all methods return null unless mocked
                ->getMock();

            $this->$name->urlGenerator = $urlgenerator;
        }

    }

    /**
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension;
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);
        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    /**
     * @dataProvider dataForCategoryTreeAdminOptionList
     */
    public function testCategoryTreeAdminOptionList($arrayToCompare, $arrayFromDb)
    {
        $this->mockedCategoryTreeAdminOptionList->categoriesArrayFromDb = $arrayFromDb;
        $arrayFromDb = $this->mockedCategoryTreeAdminOptionList->buildTree();
        $this->assertSame(
            $arrayToCompare,
            $this->mockedCategoryTreeAdminOptionList->getCategoryList($arrayFromDb)
        );
    }

    /**
     * @dataProvider dataForCategoryTreeAdminList
     */
    public function testCategoryTreeAdminList($string, $array)
    {
        $this->mockedCategoryTreeAdminList->categoriesArrayFromDb = $array;
        $array = $this->mockedCategoryTreeAdminList->buildTree();
        $this->assertSame(
            $string,
            $this->mockedCategoryTreeAdminList->getCategoryList($array)
        );
    }

    public function dataForCategoryTreeFrontPage()
    {
        yield [
            '<ul><li><a href="/video-list/category/cameras,5">Cameras</a></li><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/apple,10">Apple</a></li><li><a href="/video-list/category/asus,11">Asus</a></li><li><a href="/video-list/category/del,12">Del</a></li><li><a href="/video-list/category/lenovo,13">Lenovo</a></li><li><a href="/video-list/category/hp,14">HP</a></li></ul></li><li><a href="/video-list/category/desktops,9">Desktops</a></li></ul></li><li><a href="/video-list/category/cell-phones,7">Cell Phones</a></li></ul>',
            [
                [
                  "id" => 1,
                  "parent_id" => null,
                  "name" => "Electronics"
                ],
                [
                  "id" => 2,
                  "parent_id" => null,
                  "name" => "Toys"
                ],
                [
                  "id" => 3,
                  "parent_id" => null,
                  "name" => "Books"
                ],
                [
                  "id" => 4,
                  "parent_id" => null,
                  "name" => "Movies"
                ],
                [
                  "id" => 5,
                  "parent_id" => 1,
                  "name" => "Cameras"
                ],
                [
                  "id" => 6,
                  "parent_id" => 1,
                  "name" => "Computers"
                ],
                [
                  "id" => 7,
                  "parent_id" => 1,
                  "name" => "Cell Phones"
                ],
                [
                  "id" => 8,
                  "parent_id" => 6,
                  "name" => "Laptops"
                ],
                [
                  "id" => 9,
                  "parent_id" => 6,
                  "name" => "Desktops"
                ],
                [
                  "id" => 10,
                  "parent_id" => 8,
                  "name" => "Apple"
                ],
                [
                  "id" => 11,
                  "parent_id" => 8,
                  "name" => "Asus"
                ],
                [
                  "id" => 12,
                  "parent_id" => 8,
                  "name" => "Del"
                ],
                [
                  "id" => 13,
                  "parent_id" => 8,
                  "name" => "Lenovo"
                ],
                [
                  "id" => 14,
                  "parent_id" => 8,
                  "name" => "HP"
                ],
                [
                  "id" => 15,
                  "parent_id" => 3,
                  "name" => "Children's Books"
                ],
                [
                  "id" => 16,
                  "parent_id" => 3,
                  "name" => "Kindle eBooks"
                ],
                [
                  "id" => 17,
                  "parent_id" => 4,
                  "name" => "Family"
                ]
            ],
            1
        ];
    }

    public function dataForCategoryTreeAdminOptionList()
    {
        yield [
            [
                ["name" => "Electronics", "id" => 1],
                ["name" => "--Computers", "id" => 6],
                ["name" => "----Laptops", "id" => 8],
                ["name" => "------HP", "id" => 14]
            ],
            [
                ["name" => "Electronics", "id" => 1, "parent_id" => null],
                ["name" => "Computers", "id" => 6, "parent_id" => 1],
                ["name" => "Laptops", "id" => 8, "parent_id" => 6],
                ["name" => "HP", "id" => 14, "parent_id" => 8]
            ]
            // you can add another tests here
        ];
    }

    public function dataForCategoryTreeAdminList()
    {
        yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Toys<a href="/admin/su/edit-category/2"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/2"> Delete</a></li></ul>',
            [['id' => 2, 'parent_id' => null, 'name' => 'Toys']]
            // you can add another tests here
        ];
    }
}

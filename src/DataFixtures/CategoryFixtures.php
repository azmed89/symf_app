<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadMainCategories($manager);
        $this->loadSubcategories($manager, 'Electronics', 1);
        $this->loadSubcategories($manager, 'Computers', 6);
        $this->loadSubcategories($manager, 'Laptops', 8);
        $this->loadSubcategories($manager, 'Books', 3);
        $this->loadSubcategories($manager, 'Movies', 4);
        $this->loadSubcategories($manager, 'Romance', 18);
    }

    private function loadMainCategories($manager)
    {
        foreach ($this->getMainDataCategories() as [$name]) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();
    }

    private function loadSubcategories($manager, $category, $parent_id)
    {
        $parent = $manager->getRepository(Category::class)->find($parent_id);
        $methodName = "getMainData{$category}";
        foreach ($this->$methodName() as [$name]) {

            $category = new Category();
            $category->setName($name);
            $category->setParent($parent);
            $manager->persist($category);
        }
        $manager->flush();
    }

    private function getMainDataCategories()
    {
        return [
            ['Electronics', 1],
            ['Toys', 2],
            ['Books', 3],
            ['Movies', 4]
        ];
    }

    private function getMainDataElectronics()
    {
        return [
            ['Cameras', 5],
            ['Computers', 6],
            ['Cell Phones', 7]
        ];
    }

    private function getMainDataComputers()
    {
        return [
            ['Laptops', 8],
            ['Desktops', 9]
        ];
    }

    private function getMainDataLaptops()
    {
        return [
            ['Apple', 10],
            ['Asus', 11],
            ['Del', 12],
            ['Lenovo', 13],
            ['HP', 14]
        ];
    }

    private function getMainDataBooks()
    {
        return [
            ['Children\'s Books', 15],
            ['Kindle eBooks', 16]
        ];
    }

    private function getMainDataMovies()
    {
        return [
            ['Family', 17],
            ['Romance', 18]
        ];
    }

    private function getMainDataRomance()
    {
        return [
            ['Romantic Comedy', 19],
            ['Romantic Drama', 20]
        ];
    }
}

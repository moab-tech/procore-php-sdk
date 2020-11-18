<?php

namespace MoabTech\Procore\Api\Core;

use MoabTech\Procore\Api\Core\Company\Companies;
use MoabTech\Procore\Api\Core\Company\CompanyUploads;
use MoabTech\Procore\Api\Core\Company\ConstructionVolume;
use MoabTech\Procore\Api\Core\Company\Offices;
use MoabTech\Procore\Api\Core\Company\Programs;
use MoabTech\Procore\Api\Core\Company\ProjectBidTypes;
use MoabTech\Procore\Api\Core\Company\ProjectOwnerTypes;
use MoabTech\Procore\Api\Core\Company\ProjectRegions;
use MoabTech\Procore\Api\Core\Company\ProjectStages;
use MoabTech\Procore\Api\Core\Company\ProjectTypes;
use MoabTech\Procore\Api\Core\Company\Roles;
use MoabTech\Procore\Api\Core\Company\SubmittalStatuses;
use MoabTech\Procore\Api\Core\Company\Trades;
use MoabTech\Procore\Api\Core\CompanyDirectory\CompaniesInsurances;

trait CoreResources
{

    //  COMPANY RESOURCES

    /**
     * @return Companies
     */
    public function companies()
    {
        return new Companies($this);
    }

    /**
     * @return CompanyUploads
     */
    public function companyUploads()
    {
        return new CompanyUploads($this);
    }

    /**
     * @return ConstructionVolume
     */
    public function constructionVolume()
    {
        return new ConstructionVolume($this);
    }

    /**
     * @return Offices
     */
    public function offices()
    {
        return new Offices($this);
    }

    /**
     * @return Programs
     */
    public function programs()
    {
        return new Programs($this);
    }

    /**
     * @return ProjectBidTypes
     */
    public function projectBidTypes()
    {
        return new ProjectBidTypes($this);
    }

    /**
     * @return ProjectOwnerTypes
     */
    public function projectOwnerTypes()
    {
        return new ProjectOwnerTypes($this);
    }

    /**
     * @return ProjectRegions
     */
    public function projectRegions()
    {
        return new ProjectRegions($this);
    }

    /**
     * @return ProjectStages
     */
    public function projectStages()
    {
        return new ProjectStages($this);
    }

    /**
     * @return ProjectTypes
     */
    public function projectTypes()
    {
        return new ProjectTypes($this);
    }

    /**
     * @return Roles
     */
    public function roles()
    {
        return new Roles($this);
    }

    /**
     * @return SubmittalStatuses
     */
    public function SubmittalStatuses()
    {
        return new SubmittalStatuses($this);
    }

    /**
     * @return Trades
     */
    public function trades()
    {
        return new Trades($this);
    }

    // COMPANY DIRECTORY

    /**
     * @return CompaniesInsurances
     */
    public function companiesInsurances()
    {
        return new CompaniesInsurances($this);
    }
}

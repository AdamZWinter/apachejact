#Change all Vnet setting here before any deployments.
#While the 10.127.0.0/16 Vnet that is configured here will work,
#It is meant to be something unlikely to conflict with a Vnet you already have,
#In order to not conflict with this, you should change it now.

#RTN = Resource Tracking Name
# The trailing code should be made unique for each project
#RTNcode: jactapache2022731

# Configure the Azure provider
terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "~> 3.0.2"
    }
  }
}

provider "azurerm" {
  client_id       = var.clientid
  client_secret   = var.clientsecret
  subscription_id = var.subscriptionid
  tenant_id       = var.tenantid
  features {
    //resource_group {
    //prevent_deletion_if_contains_resources = false
    //}
  }
}

resource "azurerm_resource_group" "RTNjactapache2022731_RG" {
  name     = "jactapache2022731"
  location = "westus2"
}

# Create a virtual network within the resource group
resource "azurerm_virtual_network" "RTNjactapache2022731_Vnet" {
  name                = "jactapache2022731_vnet"
  resource_group_name = azurerm_resource_group.RTNjactapache2022731_RG.name
  location            = azurerm_resource_group.RTNjactapache2022731_RG.location
  address_space       = ["${var.networkpart}.0.0/16"]
}

resource "azurerm_subnet" "RTNjactapache2022731_Subnetzero" {
  name                 = "jactapache2022731_subnetzero"
  resource_group_name  = azurerm_resource_group.RTNjactapache2022731_RG.name
  virtual_network_name = azurerm_virtual_network.RTNjactapache2022731_Vnet.name
  address_prefixes     = ["${var.networkpart}.0.0/24"]
}

resource "azurerm_subnet" "RTNjactapache2022731_Subnetone" {
  name                 = "jactapache2022731_subnetone"
  resource_group_name  = azurerm_resource_group.RTNjactapache2022731_RG.name
  virtual_network_name = azurerm_virtual_network.RTNjactapache2022731_Vnet.name
  address_prefixes     = ["${var.networkpart}.1.0/29"]

  delegation {
    name = "subnetone_delegation"

    service_delegation {
      name = "Microsoft.ContainerInstance/containerGroups"
    }
  }
}

resource "azurerm_public_ip" "RTNjactapache2022731_PublicIPone" {
  name                = "jactapache2022731_publicip"
  sku                 = "Standard"
  resource_group_name = azurerm_resource_group.RTNjactapache2022731_RG.name
  location            = azurerm_resource_group.RTNjactapache2022731_RG.location
  allocation_method   = "Static"
  domain_name_label   = "jactapache"

  tags = {
    environment = "test"
  }
}

resource "azurerm_lb" "RTNjactapache2022731_LB" {
  name                = "jactapache2022731_loadbalancer"
  sku                 = "Standard"
  resource_group_name = azurerm_resource_group.RTNjactapache2022731_RG.name
  location            = azurerm_resource_group.RTNjactapache2022731_RG.location

  frontend_ip_configuration {
    name                 = "jactapache2022731_FEIPConfig4LB"
    public_ip_address_id = azurerm_public_ip.RTNjactapache2022731_PublicIPone.id
  }
}

resource "azurerm_lb_backend_address_pool" "RTNjactapache2022731_LBBEpool" {
  loadbalancer_id = azurerm_lb.RTNjactapache2022731_LB.id
  name            = "jactapache2022731_BackEndAddressPool"
}

resource "azurerm_lb_backend_address_pool_address" "RTNjactapache2022731_poolAddressOne" {
  name                    = "PoolAddressOne"
  backend_address_pool_id = resource.azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id
  virtual_network_id      = resource.azurerm_virtual_network.RTNjactapache2022731_Vnet.id
  ip_address              = "${var.networkpart}.1.1"
}

resource "azurerm_lb_backend_address_pool_address" "RTNjactapache2022731_poolAddressTwo" {
  name                    = "PoolAddressTwo"
  backend_address_pool_id = resource.azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id
  virtual_network_id      = resource.azurerm_virtual_network.RTNjactapache2022731_Vnet.id
  ip_address              = "${var.networkpart}.1.2"
}

resource "azurerm_lb_backend_address_pool_address" "RTNjactapache2022731_poolAddressThree" {
  name                    = "PoolAddressThree"
  backend_address_pool_id = resource.azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id
  virtual_network_id      = resource.azurerm_virtual_network.RTNjactapache2022731_Vnet.id
  ip_address              = "${var.networkpart}.1.3"
}

resource "azurerm_lb_backend_address_pool_address" "RTNjactapache2022731_poolAddressFour" {
  name                    = "PoolAddressFour"
  backend_address_pool_id = resource.azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id
  virtual_network_id      = resource.azurerm_virtual_network.RTNjactapache2022731_Vnet.id
  ip_address              = "${var.networkpart}.1.4"
}

resource "azurerm_lb_backend_address_pool_address" "RTNjactapache2022731_poolAddressFive" {
  name                    = "PoolAddressFive"
  backend_address_pool_id = resource.azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id
  virtual_network_id      = resource.azurerm_virtual_network.RTNjactapache2022731_Vnet.id
  ip_address              = "${var.networkpart}.1.5"
}

resource "azurerm_lb_backend_address_pool_address" "RTNjactapache2022731_poolAddressSix" {
  name                    = "PoolAddressSix"
  backend_address_pool_id = resource.azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id
  virtual_network_id      = resource.azurerm_virtual_network.RTNjactapache2022731_Vnet.id
  ip_address              = "${var.networkpart}.1.6"
}

resource "azurerm_lb_rule" "RTNjactapache2022731_loadBalancerRule80" {
  loadbalancer_id                = azurerm_lb.RTNjactapache2022731_LB.id
  name                           = "LBRule80"
  protocol                       = "Tcp"
  frontend_port                  = 80
  backend_port                   = 80
  frontend_ip_configuration_name = "jactapache2022731_FEIPConfig4LB"
  backend_address_pool_ids       = [azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id]
  probe_id                       = azurerm_lb_probe.RTNjactapache2022731_probe.id
}

resource "azurerm_lb_probe" "RTNjactapache2022731_probe80" {
  loadbalancer_id     = azurerm_lb.RTNjactapache2022731_LB.id
  name                = "probe80"
  port                = 80
  interval_in_seconds = 30
}

resource "azurerm_lb_rule" "RTNjactapache2022731_loadBalancerRule22" {
  loadbalancer_id                = azurerm_lb.RTNjactapache2022731_LB.id
  name                           = "LBRule22"
  protocol                       = "Tcp"
  frontend_port                  = 22
  backend_port                   = 22
  frontend_ip_configuration_name = "jactapache2022731_FEIPConfig4LB"
  backend_address_pool_ids       = [azurerm_lb_backend_address_pool.RTNjactapache2022731_LBBEpool.id]
  probe_id                       = azurerm_lb_probe.RTNjactapache2022731_probe.id
}

resource "azurerm_lb_probe" "RTNjactapache2022731_probe22" {
  loadbalancer_id     = azurerm_lb.RTNjactapache2022731_LB.id
  name                = "probe22"
  port                = 22
  interval_in_seconds = 30
}

resource "azurerm_network_profile" "RTNjactapache2022731_containergroup_profile" {
  name                = "acg-profile"
  resource_group_name = azurerm_resource_group.RTNjactapache2022731_RG.name
  location            = azurerm_resource_group.RTNjactapache2022731_RG.location

  container_network_interface {
    name = "acg-nic"

    ip_configuration {
      name      = "aciipconfig"
      subnet_id = azurerm_subnet.RTNjactapache2022731_Subnetone.id
    }
  }
}

data "azurerm_container_registry" "acr" {
  name                = "arctica"
  resource_group_name = "crrg"
}

resource "azurerm_container_group" "RTNjactapache2022731_Container" {
  name                = "jactapache2022731_container"
  resource_group_name = azurerm_resource_group.RTNjactapache2022731_RG.name
  location            = azurerm_resource_group.RTNjactapache2022731_RG.location
  ip_address_type     = "Private"
  os_type             = "Linux"
  network_profile_id  = azurerm_network_profile.RTNjactapache2022731_containergroup_profile.id
  image_registry_credential {
    username = data.azurerm_container_registry.acr.admin_username
    password = data.azurerm_container_registry.acr.admin_password
    server   = data.azurerm_container_registry.acr.login_server
  }

  container {
    name   = "mycontainername001"
    image  = var.container
    cpu    = "1.0"
    memory = "2.0"

    ports {
      port     = 80
      protocol = "TCP"
    }

    ports {
      port     = 22
      protocol = "TCP"
    }

    environment_variables = {
      "USER1" : "adam:${var.testpassword}"
      "USER2" : "dummy1:${var.testpassword}"
    }
  }


  tags = {
    environment = "testing"
  }
}

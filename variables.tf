variable "clientid" {
  description = "Azure Client ID Password"
  type        = string
  sensitive   = true
}

variable "clientsecret" {
  description = "Azure Client Service Principal Secret Password"
  type        = string
  sensitive   = true
}

variable "subscriptionid" {
  description = "Azure Subscription ID Password"
  type        = string
  sensitive   = true
}

variable "tenantid" {
  description = "Azure Tenant ID Password"
  type        = string
  sensitive   = true
}

variable "testpassword" {
  description = "Generic Global Test Password"
  type        = string
  sensitive   = true
}

variable "container" {
  description = "container tag"
  type        = string
}

variable "networkpart" {
  description = "network part of vnet"
  type        = string
}

variable "gabriel_ip" {
  default = "98.203.140.88"
}

variable "wendy_ip" {
  default = "72.107.191.201"
}

variable "adam_ip" {
  default = "71.212.127.184"
}

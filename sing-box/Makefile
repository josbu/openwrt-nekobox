#
# This is free software, licensed under the GNU General Public License v3.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=sing-box
PKG_VERSION:=1.12.0-alpha.18
PKG_RELEASE:=1

PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION).tar.gz
PKG_SOURCE_URL:=https://codeload.github.com/SagerNet/sing-box/tar.gz/v$(PKG_VERSION)?
PKG_HASH:=05b7d41ab4a37f4a4fd6514e08d49684dc8708700e8414f8df0a6626f32a3bf5

PKG_LICENSE:=GPL-3.0-or-later
PKG_LICENSE_FILES:=LICENSE

PKG_BUILD_DEPENDS:=golang/host
PKG_BUILD_PARALLEL:=1
PKG_USE_MIPS16:=0
PKG_BUILD_FLAGS:=no-mips16

GO_PKG:=github.com/sagernet/sing-box
GO_PKG_BUILD_PKG:=$(GO_PKG)/cmd/sing-box
GO_PKG_LDFLAGS_X:=$(GO_PKG)/constant.Version=v$(PKG_VERSION)

include $(INCLUDE_DIR)/package.mk
include $(TOPDIR)/feeds/packages/lang/golang/golang-package.mk

define Package/$(PKG_NAME)
  SECTION:=net
  CATEGORY:=Network
  SUBMENU:=Web Servers/Proxies
  TITLE:=The universal proxy platform.
  URL:=https://sing-box.sagernet.org
  DEPENDS:=$(GO_ARCH_DEPENDS) \
    +ca-bundle \
    +kmod-inet-diag \
    +kmod-netlink-diag \
    +kmod-tun
endef

define Package/$(PKG_NAME)/description
  Sing-box is a universal proxy platform which supports hysteria, SOCKS,
  Shadowsocks, ShadowTLS, Tor, trojan, VLess, VMess, WireGuard and so on.
endef

GO_PKG_TARGET_VARS:=$(filter-out CGO_ENABLED=%,$(GO_PKG_TARGET_VARS)) CGO_ENABLED=1

define Package/$(PKG_NAME)/config
  menu "Customizing build tags"
    depends on PACKAGE_sing-box

    config SING_BOX_WITH_ACME
      bool "Build with ACME TLS certificate issuer support"
      default y

    config SING_BOX_WITH_CLASH_API
      bool "Build with Clash API support (EXPERIMENTAL!!!)"
      default y

    config SING_BOX_WITH_DHCP
      bool "Build with DHCP support"
      default y

    config SING_BOX_WITH_ECH
      bool "Build with TLS ECH extension support"
      default y

    config SING_BOX_WITH_GRPC
      bool "Build with standard gRPC support"
      default y
      help
      sing-box has better performance gun-lite gRPC built-in by default.
      This standard gRPC has better compatibility but poor performance.

    config SING_BOX_WITH_GVISOR
      bool "Build with gVisor support"
      default y

    config SING_BOX_WITH_QUIC
      bool "Build with QUIC support"
      default y
      help
        Required by HTTP3 DNS transports, Naive inbound,
        Hysteria inbound / outbound, and v2ray QUIC transport.

    config SING_BOX_WITH_REALITY_SERVER
      bool "Build with reality TLS server support"
      default y

    config SING_BOX_WITH_UTLS
      bool "Build with uTLS support"
      default y

    config SING_BOX_WITH_V2RAY_API
      bool "Build with V2Ray API support (EXPERIMENTAL!!!)"
      default y

    config SING_BOX_WITH_WIREGUARD
      bool "Build with WireGuard support"
      default y

  endmenu
endef

PKG_CONFIG_DEPENDS:= \
	CONFIG_SING_BOX_WITH_ACME \
	CONFIG_SING_BOX_WITH_CLASH_API \
	CONFIG_SING_BOX_WITH_DHCP \
	CONFIG_SING_BOX_WITH_ECH \
	CONFIG_SING_BOX_WITH_GRPC \
	CONFIG_SING_BOX_WITH_GVISOR \
	CONFIG_SING_BOX_WITH_QUIC \
	CONFIG_SING_BOX_WITH_REALITY_SERVER \
	CONFIG_SING_BOX_WITH_UTLS \
	CONFIG_SING_BOX_WITH_V2RAY_API \
	CONFIG_SING_BOX_WITH_WIREGUARD

GO_PKG_TAGS:=with_quic,with_clash_api,with_dhcp,with_ech,with_gvisor,with_utls,with_wireguard

define Build/Compile
	@echo "Build tags: $(GO_PKG_TAGS)"
	export ENABLE_DEPRECATED_TUN_ADDRESS_X=true
	$(call GoPackage/Build/Compile)
endef

define Package/$(PKG_NAME)/conffiles
endef

define Package/$(PKG_NAME)/install
	$(call GoPackage/Package/Install/Bin,$(1))
endef

$(eval $(call GoBinPackage,sing-box))
$(eval $(call BuildPackage,sing-box))

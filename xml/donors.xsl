<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/">
    <html>
      <head>
        <title>RedTrace — Donor Registry</title>
        <style>
          body { font-family: sans-serif; background: #fdf5f5; margin: 0; padding: 2rem; }
          h1 { color: #c0392b; }
          table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
          th { background: #c0392b; color: white; padding: 0.75rem; text-align: left; font-size: 0.85rem; }
          td { padding: 0.75rem; border-bottom: 1px solid #eee; font-size: 0.9rem; }
          tr:last-child td { border-bottom: none; }
          .active { color: #27ae60; font-weight: bold; }
          .inactive { color: #888; }
        </style>
      </head>
      <body>
        <h1>Mapua RedTrace — Donor Registry</h1>
        <p>Total Donors: <strong><xsl:value-of select="count(donors/donor)"/></strong></p>
        <table>
          <thead>
            <tr>
              <th>Donor ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Blood Type</th>
              <th>Sex</th>
              <th>Registered</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <xsl:apply-templates select="donors/donor"/>
          </tbody>
        </table>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="donor">
    <tr>
      <td><xsl:value-of select="@id"/></td>
      <td><xsl:value-of select="first_name"/><xsl:text> </xsl:text><xsl:value-of select="last_name"/></td>
      <td><xsl:value-of select="email"/></td>
      <td><strong><xsl:value-of select="blood_type"/></strong></td>
      <td><xsl:value-of select="sex"/></td>
      <td><xsl:value-of select="registration_date"/></td>
      <td>
        <xsl:attribute name="class">
          <xsl:choose>
            <xsl:when test="status = 'Active'">active</xsl:when>
            <xsl:otherwise>inactive</xsl:otherwise>
          </xsl:choose>
        </xsl:attribute>
        <xsl:value-of select="status"/>
      </td>
    </tr>
  </xsl:template>

</xsl:stylesheet>

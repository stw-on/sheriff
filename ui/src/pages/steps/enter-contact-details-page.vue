<template>
  <div>
    <h2 class="font-weight-light text-center">Daten erfassen</h2>

    <v-form v-model="isValid">
      <v-row dense>
        <v-col cols="6">
          <v-text-field
            v-model="firstName"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Vorname"
          />
        </v-col>
        <v-col cols="6">
          <v-text-field
            v-model="lastName"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Nachname"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="street"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Straße + Nr."
          />
        </v-col>
        <v-col cols="4">
          <v-text-field
            v-model="zip"
            :rules="[v => !!v, v => v.length === 5]"
            hide-details
            maxlength="5"
            outlined
            placeholder="PLZ"
          />
        </v-col>
        <v-col cols="8">
          <v-text-field
            v-model="city"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Ort"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="phone"
            :rules="[v => !!v, v => v.length >= 2]"
            hide-details
            maxlength="128"
            outlined
            placeholder="Telefon- oder Handynummer"
          />
        </v-col>
      </v-row>
      <v-row no-gutters>
        <v-col cols="12">
          <v-checkbox
            v-if="!hasSavedContactDetails"
            v-model="saveContactDetails"
            label="Meine Daten für das nächste Mal auf diesem Gerät speichern"
            hide-details
          />
          <a v-else @click="deleteSavedContactDetails">Gespeicherte Daten von diesem Gerät löschen</a>
        </v-col>
        <v-col cols="12">
          <v-checkbox
            v-model="acceptedPrivacy"
            :rules="[v => !!v]"
          >
            <template v-slot:label>
              <div>
                Ich habe die <a href="#" @click.prevent.stop="showPrivacyPolicy = true">Hinweise zum Datenschutz</a> gelesen und stimme ihnen zu.
              </div>
            </template>
          </v-checkbox>
        </v-col>
        <v-col class="text-center" cols="12">
          <div>
            <v-btn
              :disabled="!isValid || offline"
              :loading="loading"
              class="mt-4"
              color="primary"
              large
              @click="proceed"
            >
              <template v-if="offline">
                <v-icon left>mdi-cloud-off-outline</v-icon>
                Offline
              </template>
              <template v-else>
                Weiter
                <v-icon right>mdi-chevron-right</v-icon>
              </template>
            </v-btn>
          </div>
          <div>
            <v-btn v-if="saveContactDetails" :disabled="!isValid || saved" text class="mt-4" color="primary" large @click="save">
              <template v-if="saved">
                <v-icon left>mdi-check</v-icon>
                Gespeichert
              </template>
              <template v-else>
                <v-icon left>mdi-content-save-outline</v-icon>
                Daten nur speichern
              </template>
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </v-form>

    <v-dialog v-model="showPrivacyPolicy" fullscreen transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="showPrivacyPolicy = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Hinweise zum Datenschutz</v-toolbar-title>
        </v-toolbar>

        <div class="pa-3">
          <p>
            Sie haben in der Check-In Webanwendung personenbezogene Daten angegeben. Diese Hinweise informieren Sie
            unter anderem darüber, wer Ihre Daten zu welchem Zweck erhebt und verarbeitet und informiert Sie über Ihre
            Rechte.
          </p>

          <h2>1. Verantwortlicher</h2>
          <p>
            Verantwortlicher für die Erhebung, Verarbeitung und Nutzung Ihrer personenbezogenen Daten ist das<br>
            <br>
            Studentenwerk OstNiedersachsen<br>
            Katharinenstr. 1<br>
            38106 Braunschweig<br>
            Tel.: (0531) 391-4807<br>
            E-Mail: info@stw-on.de
          </p>

          <h2>Datenschutzbeauftragter</h2>
          <p>
            Sollten Sie Fragen oder Bedenken zum Datenschutz haben, so wenden Sie sich bitte an unseren
            Datenschutzbeauftragten:<br>
            <br>
            Mirko Pidde<br>
            Katharinenstr. 1<br>
            38106 Braunschweig<br>
            Tel.: (0531) 391-4974<br>
            E-Mail: datenschutz@stw-on.de
          </p>

          <h2>Zweck der Verarbeitung</h2>
          <p>
            Wir verwenden die über die Webanwendung von Ihnen erhobenen personenbezogenen Daten zur Erfüllung unserer
            Pflichten zur Datenerhebung und Dokumentation in gastronomischen Betrieben gemäß der Niedersächsischen
            Corona-Verordnung.
          </p>

          <h2>Grundlage für die Erhebung und Verarbeitung Ihrer Daten</h2>
          <p>
            Wir benötigen die Daten zur Erfüllung einer gesetzlichen Verpflichtung, der wir unterliegen. Diese begründet
            sich aus §4 Abs. 1 der Niedersächsischen Corona-Verordnung.
          </p>

          <h2>Weitergabe von Daten an Dritte</h2>
          <p>
            Wir geben die von Ihnen gemachten Angaben ausschließlich an das zuständige Gesundheitsamt im Falle einer
            vorliegenden Covid-19-Infektion weiter.
          </p>

          <h2>Speicherdauer</h2>
          <p>
            Ihre personenbezogenen Daten werden für die Dauer von 14 Tagen gespeichert. Nach diesem Zeitpunkt erfolgt
            die Löschung der Daten, insofern keine gesetzlichen Aufbewahrungspflichten dem entgegenstehen.
          </p>

          <h2>Ihre Rechte als von der Datenverarbeitung Betroffener</h2>
          <p>
            Sie haben gegenüber dem Studentenwerk OstNiedersachsen das Recht auf Auskunft über die Sie betreffenden
            personenbezogenen Daten. Außerdem haben Sie das Recht auf Berichtigung, Löschung oder Einschränkung der
            Verarbeitung dieser Daten Weiterhin können Sie der Verarbeitung der Daten widersprechen und haben ein Recht
            auf Übertragbarkeit der Daten.
          </p>

          <h2>Recht auf Beschwerde bei einer Aufsichtsbehörde</h2>
          <p>
            Sie haben das Recht auf Beschwerde bei der Landesbeauftragten für den Datenschutz Niedersachsen, Prinzenstr.
            5, 30159 Hannover, E-Mail: poststelle@lfd.niedersachsen.de
          </p>

          <h2>Verpflichtung zur Angabe von Daten und mögliche Folgen einer Nichtbereitstellung</h2>
          <p>
            Im Rahmen der Niedersächsischen Corona-Verordnung. sind wir verpflichtet, die in der Webanwendung
            angegebenen Angaben zu erheben. Falls Sie uns diese Daten nicht bereitstellen, können wir Ihnen den Zutritt
            zu unseren gastronomischen Einrichtungen nicht gewähren
          </p>

          <h2>Verarbeitung von Daten für einen anderen Zweck</h2>
          <p>
            Sollte das Studentenwerk OstNiedersachsen beabsichtigen, Ihre Daten für einen anderen Zweck als unter Punkt
            3 angegeben weiterzuverarbeiten, stellt es Ihnen vor dieser Weiterverarbeitung Informationen über diesen
            anderen Zweck und alle anderen maßgeblichen Informationen zur Verfügung.
          </p>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    name: 'enter-contact-details-page',
    props: {
      loading: Boolean,
      offline: Boolean,
    },
    data: () => ({
      firstName: '',
      lastName: '',
      street: '',
      zip: '',
      city: '',
      phone: '',
      saveContactDetails: true,
      hasSavedContactDetails: false,
      isValid: false,
      saved: false,
      showPrivacyPolicy: false,
      acceptedPrivacy: false,
    }),
    computed: {
      contactDetails() {
        return {
          first_name: this.firstName,
          last_name: this.lastName,
          street: this.street,
          zip: this.zip,
          city: this.city,
          phone: this.phone,
        }
      },
    },
    watch: {
      contactDetails() {
        this.saved = false
      },
    },
    mounted() {
      const savedData = window.localStorage.getItem('sheriff_data')

      if (savedData) {
        try {
          const data = JSON.parse(savedData)
          this.firstName = data.first_name
          this.lastName = data.last_name
          this.street = data.street
          this.zip = data.zip
          this.city = data.city
          this.phone = data.phone
          this.saveContactDetails = true
          this.hasSavedContactDetails = true
          this.acceptedPrivacy = true

          this.$nextTick(() => this.saved = true)
        } catch (e) {
          console.error(e)
        }
      }
    },
    methods: {
      save() {
        window.localStorage.setItem('sheriff_data', JSON.stringify(this.contactDetails))
        this.saved = true
        this.hasSavedContactDetails = true
      },
      proceed() {
        if (this.saveContactDetails) {
          this.save()
        }

        this.$emit('proceed', this.contactDetails)
      },
      deleteSavedContactDetails() {
        window.localStorage.removeItem('sheriff_data')
        this.hasSavedContactDetails = false
        this.firstName = ''
        this.lastName = ''
        this.street = ''
        this.zip = ''
        this.city = ''
        this.phone = ''
      },
    },
  }
</script>

<style lang="scss" scoped>

</style>
